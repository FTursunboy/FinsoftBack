<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\EquipmentDocumentDTO;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Models\Document;
use App\Models\Equipment;
use App\Models\EquipmentGoods;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\Status;
use App\Repositories\Contracts\Document\ClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\EquipmentDocumentRepositoryInterface;
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

class EquipmentDocumentRepository implements EquipmentDocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait, CalculateSum;

    public $model = Equipment::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::query();

        $query = $this->search($query, $filteredParams);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['organization', 'storage', 'good', 'documentGoods', 'documentGoods.good', 'author']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(EquipmentDocumentDTO $dto): Equipment
    {

        $document = DB::transaction(function () use ($dto) {

            $document = Equipment::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'organization_id' => $dto->organization_id,
                'storage_id' => $dto->storage_id,
                'good_id' => $dto->good_id,
                'author_id' => Auth::id(),
                'comment' => $dto->comment,
                'sum' => $dto->sum,
                'amount' => $dto->amount
            ]);


            EquipmentGoods::insert($this->insertGoodDocuments($dto->goods, $document));

            return $document;
        });

        return $document->load(['organization', 'storage', 'author', 'good', 'documentGoods', 'documentGoods.good', 'author']);

    }

    public function update(Equipment $document, EquipmentDocumentDTO $dto)
    {

        return DB::transaction(function () use ($dto, $document) {
            $document->update([
                'date' => $dto->date,
                'organization_id' => $dto->organization_id,
                'storage_id' => $dto->storage_id,
                'good_id' => $dto->good_id,
                'author_id' => Auth::id(),
                'comment' => $dto->comment,
                'sum' => $dto->sum,
                'amount' => $dto->amount
            ]);

            if (!is_null($dto->goods)) {
                $this->updateGoodDocuments($dto->goods, $document);
            }

            $data['ids'][] = $document->id;

            if ($document->active) $this->approve($data);
        });
    }

    private function insertGoodDocuments(array $goods, Equipment $document): array
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'sum' => $item['sum'],
                'equipment_document_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, Equipment $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {

                $goodDocument = EquipmentGoods::where('id', $good['id'])->first();

                $goodDocument->update([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'sum' => $good['sum'],
                    'equipment_document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]);

            } else {
                EquipmentGoods::create([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'sum' => $good['sum'],
                    'equipment_document_id' => $document->id,
                    'created_at' => Carbon::now()
                ]);

            }
        }
    }

    public function deleteDocumentData(Document $document)
    {
        //
    }


    public function approve(array $data)
    {
        try {

            foreach ($data['ids'] as $id) {
                $document = Equipment::find($id);

                if ($document->active) {
                    $this->deleteDocumentData($document);

                    $document->update(
                        ['active' => false]
                    );
                }

                $document->update(
                    ['active' => true]
                );

            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }

    public function unApprove(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = Equipment::find($id);

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
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('storage', function ($query) use ($searchTerm) {
                    return $query->where('storages.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('good', function ($query) use ($searchTerm) {
                    return $query->where('goods.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['organization_id'], function ($query) use ($data) {
            return $query->where('organization_id', $data['organization_id']);
        })
            ->when($data['storage_id'], function ($query) use ($data) {
                return $query->where('storage_id', $data['storage_id']);
            })
            ->when($data['good_id'], function ($query) use ($data) {
                return $query->where('good_id', $data['good_id']);
            })
            ->when(isset($data['active']), function ($query) use ($data) {
                return $query->where('active', $data['active']);
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
