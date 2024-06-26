<?php

namespace App\Repositories\Document;

use App\DTO\Document\OrderDocumentDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\OrderProviderDocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderProviderDocumentRepository implements OrderProviderDocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait;

    public $model = OrderDocument::class;

    public function index(array $data, int $type): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::query()->where('order_type_id', $type);

        $query = $this->orderSearch($query, $filteredParams);

        $query = $this->orderFilter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'orderStatus', 'author', 'counterpartyAgreement', 'currency']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(OrderDocumentDTO $DTO, int $type)
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
            ->when($data['startDate'], function ($query) use ($data) {
                return $query->where('date', '>=', $data['startDate']);
            })
            ->when($data['endDate'], function ($query) use ($data) {
                return $query->where('date', '<=', $data['endDate']);
            })
            ->when($data['author_id'], function ($query) use ($data) {
                return $query->where('author_id', $data['author_id']);
            })
            ->when(isset($data['active']), function ($query) use ($data) {
                return $query->where('active', $data['active']);
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

    public function approve(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = $this->model::find($id);

            $document->update(
                ['active' => true]
            );
        }
    }


    public function unApprove(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = $this->model::find($id);

            $document->update(
                ['active' => false]
            );
        }
    }

    public function copy(OrderDocument $document)
    {
        $goods = $document->orderDocumentGoods->toArray();

        $document = DB::transaction(function () use ($document, $goods) {
            $document = OrderDocument::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => Carbon::parse($document->date),
                'counterparty_id' => $document->counterparty_id,
                'counterparty_agreement_id' => $document->counterparty_agreement_id,
                'organization_id' => $document->organization_id,
                'order_status_id' => $document->order_status_id,
                'author_id' => Auth::id(),
                'comment' => $document->comment,
                'summa' => $document->summa,
                'shipping_date' => $document->shipping_date,
                'currency_id' => $document->currency_id,
                'order_type_id' => $document->order_type_id,
            ]);

            OrderDocumentGoods::insert($this->orderGoods($document, $goods));

            return $document;
        });

        return $document->load('counterparty', 'organization', 'author', 'currency', 'counterpartyAgreement', 'orderDocumentGoods', 'orderStatus');
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $document = $this->model::where('id', $id)->first();
                $document->update([
                    'deleted_at' => Carbon::now(),
                    'active' => 0
                ]);

            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
