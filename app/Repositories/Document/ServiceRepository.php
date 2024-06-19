<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\ServiceDTO;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Enums\ServiceTypes;
use App\Events\Document\DocumentApprovedEvent;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\Service;
use App\Models\ServiceGoods;
use App\Models\Status;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\ServiceRepositoryInterface;
use App\Traits\CalculateSum;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceRepository implements ServiceRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait, CalculateSum;

    public $model = Service::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::query()->where('status_id', Status::CLIENT_PURCHASE);

        $query = $this->search($query, $filteredParams);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoodsWithCount', 'totalGoodsSum']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(ServiceDto $dto)
    {
        DB::beginTransaction();

        try {
            $service = Service::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'counterparty_id' => $dto->counterparty_id,
                'counterparty_agreement_id' => $dto->counterparty_agreement_id,
                'storage_id' => $dto->storage_id,
                'organization_id' => $dto->organization_id,
                'author_id' => Auth::id(),
                'comment' => $dto->comment,
                'currency_id' => $dto->currency_id,
                'sales_sum' => $dto->sales_sum,
                'return_sum' => $dto->return_sum,
                'client_payment' => $dto->client_payment,
            ]);

            if (!is_null($dto->sale_goods)) {
                ServiceGoods::insert($this->insertGoodDocuments($dto->sale_goods, $service, ServiceTypes::Sale));
            }
            if (!is_null($dto->return_goods)) {
                ServiceGoods::insert($this->insertGoodDocuments($dto->return_goods, $service, ServiceTypes::Return));
            }

            $errors = [];

            if ($dto->approve) {
                $this->handleApproval($dto, $errors);
            }

            if (!empty($errors)) {
                DB::rollBack();
                return ['errors' => $errors];
            }

            if ($dto->approve) {
                $service->update(['active' => true]);
            }

            DB::commit();

            return $service;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    private function handleApproval(ServiceDto $dto, &$errors)
    {
        $this->processGoods($dto, $dto->sale_goods, __('errors.not enough goods in sale'), $errors);
        $this->processGoods($dto, $dto->return_goods, __('errors.not enough goods in return'), $errors);
    }

    private function processGoods(ServiceDto $dto, $goods, $errorMessage, &$errors)
    {
        if (!is_null($goods)) {
            $documentRepository = $this->getDocumentRepository($errorMessage);
            $document = $documentRepository->store(DocumentDTO::fromServiceDTO($dto, $goods));
            $ids = ['ids' => [$document->id]];
            $response = $documentRepository->approve($ids);
            if ($response !== null) {
                $document->forceDelete();
                $errors[] = [
                    'error' => $errorMessage,
                    'goods' => $response
                ];
            }
        }
    }

    private function getDocumentRepository($errorMessage)
    {
        return $errorMessage === __('errors.not enough goods in sale') ? new ClientDocumentRepository() : new ReturnDocumentRepository();
    }


    public function update(Service $document, ServiceDTO $dto)
    {

    }

    private function insertGoodDocuments(array $goods, Service $document, ServiceTypes $type): array
    {
        return array_map(function ($item) use ($document, $type) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'type' => $type,
                'service_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, Document $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {

                $goodDocument = GoodDocument::where('id', $good['id'])->first();

                $goodDocument->update([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
                    'document_id' => $document->id,
                    'auto_sale_sum' => $good['auto_sale_sum'] ?? null,
                    'updated_at' => Carbon::now()
                ]);

            } else {
                GoodDocument::create([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'document_id' => $document->id,
                    'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
                    'auto_sale_sum' => $good['auto_sale_sum'] ?? null,
                    'updated_at' => Carbon::now()
                ]);

            }
        }
    }

    public function changeHistory(Documentable $document)
    {
        return $document->load(['history.changes', 'history.user']);
    }

    public function deleteDocumentData(Document $document)
    {
        $document->goodAccountents()->delete();

        $document->counterpartySettlements()->delete();

        $document->balances()->delete();
    }


    public function approve(array $data)
    {
        try {
            foreach ($data['ids'] as $id) {
                $document = Document::find($id);

                if ($document->active) {
                    $this->deleteDocumentData($document);

                    $document->update(
                        ['active' => false]
                    );
                }

                $result = $this->checkInventory($document);

                $response = [];

                if ($result !== null) {
                    foreach ($result as $goods) {
                        $good = Good::find($goods['good_id'])->name;

                        $response[] = [
                            'amount' => $goods['amount'],
                            'good' => $good,
                        ];
                    }

                    return $response;
                }

                $document->update(
                    ['active' => true]
                );


                DocumentApprovedEvent::dispatch($document, MovementTypes::Outcome, DocumentTypes::SaleToClient->value);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }


    public function checkInventory(Document $document)
    {
        $incomingDate = $document->date;

        $incomingGoods = $document->documentGoods->pluck('good_id', 'amount')->toArray();

        $previousIncomings = GoodAccounting::where('movement_type', MovementTypes::Income)
            ->where('storage_id', $document->storage_id)
            ->where('date', '<=', $incomingDate)
            ->get();

        $previousOutgoings = GoodAccounting::where('movement_type', MovementTypes::Outcome)
            ->where('date', '<=', $incomingDate)
            ->where('storage_id', $document->storage_id)
            ->get();

        $previousIncomingsByGoodId = $previousIncomings->groupBy('good_id')->map(function ($group) {
            return $group->sum('amount');
        });

        $previousOutgoingsByGoodId = $previousOutgoings->groupBy('good_id')->map(function ($group) {
            return $group->sum('amount');
        });


        $insufficientGoods = [];

        foreach ($incomingGoods as $incomingAmount => $goodId) {


            $totalIncoming = $previousIncomingsByGoodId->has($goodId) ? $previousIncomingsByGoodId[$goodId] : 0;
            $totalOutgoing = $previousOutgoingsByGoodId->has($goodId) ? $previousOutgoingsByGoodId[$goodId] : 0;

            $availableAmount = $totalIncoming - $totalOutgoing;

            if ($incomingAmount > $availableAmount) {
                $insufficientGoods[] = [
                    'good_id' => $goodId,
                    'amount' => $incomingAmount - $availableAmount
                ];
            }

        }

        if (!empty($insufficientGoods)) {
            return $insufficientGoods;
        }
    }

    public function unApprove(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = Document::find($id);

            $this->deleteDocumentData($document);
            $document->update(
                ['active' => false]
            );
        }
    }

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO)
    {
        // TODO: Implement deleteDocumentGoods() method.
    }


    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $document = $this->model::where('id', $id)->first();
                $document->goodAccountents()->delete();
                $document->counterpartySettlements()->delete();
                $document->balances()->delete();
                $document->update([
                    'deleted_at' => Carbon::now(),
                    'active' => 0
                ]);

            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function search($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('counterparty', function ($query) use ($searchTerm) {
                    return $query->where('counterparties.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('currency', function ($query) use ($searchTerm) {
                    return $query->where('currencies.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['counterparty_id'], function ($query) use ($data) {
                return $query->where('counterparty_id', $data['counterparty_id']);
            })
            ->when($data['order_status_id'], function ($query) use ($data) {
                return $query->where('order_status_id', $data['order_status_id']);
            })
            ->when(isset($data['active']), function ($query) use ($data) {
                return $query->where('active', $data['active']);
            })
            ->when($data['counterparty_agreement_id'], function ($query) use ($data) {
                return $query->where('counterparty_agreement_id', $data['counterparty_agreement_id']);
            })
            ->when($data['storage_id'], function ($query) use ($data) {
                return $query->where('storage_id', $data['storage_id']);
            })
            ->when($data['startDate'], function ($query) use ($data) {
                return $query->where('date', '>=', $data['startDate']);
            })
            ->when($data['endDate'], function ($query) use ($data) {
                return $query->where('date', '<=', $data['endDate']);
            })
            ->when($data['author_id'], function ($query) use ($data) {
                return $query->where('author_id', $data['author_id']);
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }



}
