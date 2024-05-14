<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Models\Status;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentRepository implements DocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait;

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
        try {
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

                $this->calculateSum($document);


        }
        catch (\Exception $exception) {
            Log::error($exception);
        }

        return $document->load(['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods', 'documentGoods.good']);
    }

    public function update(Document $document, DocumentUpdateDTO $dto)
    {
        return DB::transaction(function () use ($dto, $document) {
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
        });
    }


    public function order(OrderDocumentDTO $DTO, int $type)
    {
        $document = DB::transaction(function () use ($DTO, $type) {
            $document = OrderDocument::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => Carbon::parse($DTO->date),
                'counterparty_id' => $DTO->counterparty_id,
                'counterparty_agreement_id' => $DTO->counterparty_agreement_id,
                'organization_id' => $DTO->organization_id,
                'order_status_id' => $DTO->order_status_id,
                'author_id' => Auth::id(),
                'comment' => $DTO->comment,
                'summa' => $DTO->summa,
                'shipping_date' => $DTO->shipping_date,
                'currency_id' => $DTO->currency_id,
                'order_type_id' => $type,
            ]);
 //dsa
            OrderDocumentGoods::insert($this->orderGoods($document, $DTO->goods));

            return $document;
        });
        return $document->load('counterparty', 'organization', 'author', 'currency', 'counterpartyAgreement', 'orderDocumentGoods', 'orderStatus');

    }

    public function updateOrder(OrderDocument $document, OrderDocumentUpdateDTO $DTO): OrderDocument
    {
        $document = DB::transaction(function () use ($DTO, $document) {
            $document->update([
                'doc_number' => $document->doc_number,
                'date' => Carbon::parse($DTO->date),
                'counterparty_id' => $DTO->counterparty_id,
                'counterparty_agreement_id' => $DTO->counterparty_agreement_id,
                'organization_id' => $DTO->organization_id,
                'order_status_id' => $DTO->order_status_id,
                'author_id' => Auth::id(),
                'comment' => $DTO->comment,
                'summa' => $DTO->summa,
                'shipping_date' => $DTO->shipping_date,
                'currency_id' => $DTO->currency_id

            ]);

            if (!is_null($DTO->goods))
                $this->updateOrderGoods($document, $DTO->goods);

            return $document;
        });
        return $document->load('counterparty', 'organization', 'author', 'currency', 'counterpartyAgreement', 'orderDocumentGoods', 'orderStatus');
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

            $document->update(
                ['active' => true]
            );
            DocumentApprovedEvent::dispatch($document, MovementTypes::Income);
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
        return $document->load(['history.changes', 'history.user']);
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


    private function calculateSum(Document $document)
    {
        $goods = $document->documentGoods;
        $sum = 0;
        $saleSum = 0;

        foreach ($goods as $good) {
            $basePrice = $good->price * $good->amount;
            $sum += $basePrice;

            $discountAmount = 0;
            if (isset($good->auto_sale_percent)) {
                $discountAmount += $basePrice * ($good->auto_sale_percent / 100);
            }
            if (isset($good->auto_sale_sum)) {
                $discountAmount += $good->auto_sale_sum;
            }

            $priceAfterGoodDiscount = $basePrice - $discountAmount;
            $saleSum += $priceAfterGoodDiscount;
        }

        $documentDiscount = 0;
        if (isset($document->salePercent)) {
            $documentDiscount += $saleSum * ($document->salePercent / 100);
        }
        if (isset($document->saleInteger)) {
            $documentDiscount += $document->saleInteger;
        }

        $saleSum -= $documentDiscount;

        $document->sum = $sum;
        $document->sale_sum = $saleSum;

        $document->save();

    }


}
