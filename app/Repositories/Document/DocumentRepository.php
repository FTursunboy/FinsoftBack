<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Jobs\SendPushJob;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Models\Status;
use App\Models\User;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Traits\CalculateSum;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentRepository implements DocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait, CalculateSum;

    public $model = Document::class;

    public function index(int $status, array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::query()->where('status_id', $status);

        $query = $this->search($query, $filteredParams);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoodsWithCount', 'totalGoodsSum']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }
    public function store(DocumentDTO $dto, int $status): Document
    {
        $document = DB::transaction(function () use ($dto, $status) {
            $document = Document::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'counterparty_id' => $dto->counterparty_id,
                'counterparty_agreement_id' => $dto->counterparty_agreement_id,
                'storage_id' => $dto->storage_id,
                'organization_id' => $dto->organization_id,
                'author_id' => Auth::id(),
                'status_id' => $status,
                'comment' => $dto->comment,
                'saleInteger' => $dto->saleInteger,
                'salePercent' => $dto->salePercent,
                'currency_id' => $dto->currency_id
            ]);

            GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));

            $this->calculateSum($document, true);

            return $document;
        });
        $user = User::find($document->author_id);

        SendPushJob::dispatch($user, ['title' => DocumentTypes::Purchase, 'body' => 'Документ успешно создан'], $document, 'document');

        return $document->load(['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods', 'documentGoods.good']);
    }

    public function update(Document $document, DocumentUpdateDTO $dto)
    {
        DB::transaction(function () use ($dto, $document) {
            $document->update([
                'doc_number' => $document->doc_number,
                'date' => $dto->date,
                'counterparty_id' => $dto->counterparty_id,
                'counterparty_agreement_id' => $dto->counterparty_agreement_id,
                'organization_id' => $dto->organization_id,
                'comment' => $dto->comment,
                'storage_id' => $dto->storage_id,
                'saleInteger' => $dto->saleInteger,
                'salePercent' => $dto->salePercent,
                'currency_id' => $dto->currency_id
            ]);

            if (!is_null($dto->goods)) {
                $this->updateGoodDocuments($dto->goods, $document);
            }

            $this->calculateSum($document);

            $data['ids'][] = $document->id;

            if ($document->active) $this->approve($data);
        });
    }

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO)
    {
        GoodDocument::whereIn('id', $DTO->ids)->delete();
    }


    private function insertGoodDocuments(array $goods, Document $document): array
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'document_id' => $document->id,
                'auto_sale_percent' => $item['auto_sale_percent'] ?? null,
                'auto_sale_sum' => $item['auto_sale_sum'] ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, Document $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {
                GoodDocument::updateOrCreate(
                    ['id' => $good['id']],
                    [
                        'good_id' => $good['good_id'],
                        'amount' => $good['amount'],
                        'document_id' => $document->id,
                        'price' => $good['price'],
                        'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
                        'auto_sale_sum' => $good['auto_sale_sum'] ?? null,
                        'updated_at' => Carbon::now()
                    ]
                );
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

    public function goodDocument($good, $document): array
    {
        return [
            'good_id' => $good['good_id'],
            'amount' => $good['amount'],
            'price' => $good['price'],
            'document_id' => $document->id,
            'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
            'auto_sale_sum' => $good['auto_sale_sum'] ?? null,
            'updated_at' => Carbon::now()
        ];
    }

    public function approve(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = Document::find($id);

            if($document->active) {
                $document->update(
                    ['active' => false]
                );
                $this->deleteDocumentData($document);
            }

            $document->update(
                ['active' => true]
            );

            DocumentApprovedEvent::dispatch($document, MovementTypes::Income, DocumentTypes::Purchase->value);
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

    public function deleteDocumentData(Document $document)
    {
        $document->goodAccountents()->delete();
        $document->counterpartySettlements()->delete();
        $document->balances()->delete();
    }


    public function changeHistory(Documentable $document)
    {
        return $document->load(['history.changes', 'history.user', 'history.changes.changeGoods']);
    }

    public function createOnBase(Documentable $document)
    {
        $modelClass = get_class($document);

        // Проверяем, является ли переданная модель экземпляром OrderDocument
        if ($document instanceof OrderDocument) {
            return $document->load(['counterparty', 'organization', 'author', 'orderDocumentGoods.goods', 'orderStatus', 'counterpartyAgreement', 'currency']);
        }

        return $document->load(['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods.good']);
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
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
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
            ->when($data['counterparty_agreement_id'], function ($query) use ($data) {
                return $query->where('counterparty_agreement_id', $data['counterparty_agreement_id']);
            })
            ->when($data['active'], function ($query) use ($data) {
                return $query->where('active', $data['active']);
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


    public function approveClient(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = Document::find($id);


            $result = $this->checkInventory($document);

            $response = [];

            if ($result !== null) {
                foreach ($result as $goods) {

                    $good = Good::find($goods['good_id'])->name;

                    $response[] = [
                        'good' => $good,
                        'amount' => $goods['amount']
                    ];
                }
                return $response;
            }

            if ($document->active) {
                $this->deleteDocumentData($document);
            }

            $document->update(
                ['active' => true]
            );

            DocumentApprovedEvent::dispatch($document, MovementTypes::Outcome);
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


    public function copy(Document $document)
    {
        $goods = $document->documentGoods->toArray();

        $document = DB::transaction(function () use ($document, $goods) {
            $document = Document::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $document->date,
                'counterparty_id' => $document->counterparty_id,
                'counterparty_agreement_id' => $document->counterparty_agreement_id,
                'storage_id' => $document->storage_id,
                'organization_id' => $document->organization_id,
                'author_id' => Auth::id(),
                'status_id' => $document->status_id,
                'comment' => $document->comment,
                'saleInteger' => $document->saleInteger,
                'salePercent' => $document->salePercent,
                'currency_id' => $document->currency_id,
                'sum' => $document->sum
            ]);

            GoodDocument::insert($this->insertGoodDocuments($goods, $document));

            return $document;
        });

        return $document->load(['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods', 'documentGoods.good']);
    }
}
