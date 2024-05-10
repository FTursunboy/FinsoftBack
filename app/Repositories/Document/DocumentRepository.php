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

    public function orderList(array $data, int $type): LengthAwarePaginator
    {
        $filteredParams = OrderDocument::filter($data);

        $query = OrderDocument::query()->where('order_type_id', $type);

        $query = $this->orderSearch($query, $filteredParams);

        $query = $this->orderFilter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'orderStatus', 'author', 'counterpartyAgreement', 'currency']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(DocumentDTO $dto, int $status): Document
    {
        $document = DB::transaction(function () use ($status, $dto) {

            $document = Document::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'counterparty_id' => $dto->counterparty_id,
                'counterparty_agreement_id' => $dto->counterparty_agreement_id,
                'organization_id' => $dto->organization_id,
                'storage_id' => $dto->storage_id,
                'author_id' => Auth::id(),
                'status_id' => $status,
                'comment' => $dto->comment,
                'saleInteger' => $dto->saleInteger,
                'salePercent' => $dto->salePercent,
                'currency_id' => $dto->currency_id,
                'sale_sum' => $dto->sale_sum,
                'sum' => $dto->sum,
            ]);


            if (!is_null($dto->goods))
                GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));

            return $document;

          });

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
                'storage_id' => $dto->storage_id,
                'comment' => $dto->comment,
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
                'doc_number' => $this->orderUniqueNumber(),
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

            if (!is_null($DTO->goods))
                OrderDocumentGoods::insert($this->orderGoods($document, $DTO->goods));

            return $document;
           });
        return  $document->load('counterparty', 'organization', 'author', 'currency', 'counterpartyAgreement', 'orderDocumentGoods', 'orderStatus');

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

    public function orderUniqueNumber(): string
    {
        $lastRecord = OrderDocument::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
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
                        'price' => $good['price'],
                        'document_id' => $document->id,
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

    public function goodDocument($good, $document)
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

    public function approve(Document $document)
    {
        $document->update(
            ['active' => true]
        );
        if ($document->status_id === Status::PROVIDER_PURCHASE || $document->status_id === Status::CLIENT_PURCHASE)
        {
            DocumentApprovedEvent::dispatch($document, MovementTypes::Income);
        }

    }

    public function unApprove(Document $document)
    {
        $this->deleteDocumentData($document);
        $document->update(
            ['active' => false]
        );
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

    public function orderGoods(OrderDocument $document, array $goods): array
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'auto_sale_percent' => $item['auto_sale_percent'] ?? null,
                'auto_sale_sum' => $item['auto_sale_sum'] ?? null,
                'order_document_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    public function updateOrderGoods(OrderDocument $document, array $goods)
    {
        foreach ($goods as $good) {
            OrderDocumentGoods::updateOrCreate(
                ['id' => $good['id']],
                [
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
                    'auto_sale_sum' => $good['auto_sale_sum'] ?? null,
                    'order_document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }

    public function search($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('counterparty', function ($query) use ($searchTerm) {
                    return $query->where('counterparties.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('currency', function ($query) use ($searchTerm) {
                    return $query->where('currencies.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function orderSearch($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('counterparty', function ($query) use ($searchTerm) {
                    return $query->where('counterparties.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
//                ->orWhereHas('order_status', function ($query) use ($searchTerm) {
//                    return $query->where('order_statuses.name', 'like', '%' . implode('%', $searchTerm) . '%');
//                })
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
            ->when($data['counterparty_id'], function ($query) use ($data) {
                return $query->where('counterparty_id', $data['counterparty_id']);
            })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['counterparty_agreement_id'], function ($query) use ($data) {
                return $query->where('counterparty_agreement_id', $data['counterparty_agreement_id']);
            })
            ->when($data['storage_id'], function ($query) use ($data) {
                return $query->where('storage_id', $data['storage_id']);
            })
            ->when($data['date'], function ($query) use ($data) {
                $date = Carbon::createFromFormat('Y-m-d', $data['date'])->format('Y-m-d');
                return $query->where('date', $date);
            })
            ->when($data['author_id'], function ($query) use ($data) {
                return $query->where('author_id', $data['author_id']);
            });
    }

    public function orderFilter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['counterparty_id'], function ($query) use ($data) {
                return $query->where('counterparty_id', $data['counterparty_id']);
            })
            ->when($data['order_status_id'], function ($query) use ($data) {
                return $query->where('order_status_id', $data['order_status_id']);
            })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['counterparty_agreement_id'], function ($query) use ($data) {
                return $query->where('counterparty_agreement_id', $data['counterparty_agreement_id']);
            })
            ->when($data['date'], function ($query) use ($data) {
                return $query->where('date', $data['date']);
            })
            ->when($data['author_id'], function ($query) use ($data) {
                return $query->where('author_id', $data['author_id']);
            });
    }

}
