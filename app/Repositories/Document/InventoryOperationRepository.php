<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\InventoryOperationDTO;
use App\Enums\MovementTypes;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\InventoryOperation;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\InventoryOperationRepositoryInterface;
use App\Traits\CalculateSum;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryOperationRepository implements InventoryOperationRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait, CalculateSum;

    public $model = InventoryOperation::class;

    public function index(string $type, array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        $query = $query->where('status', $type);

        $query = $this->search($query, $filteredParams);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(InventoryOperationDTO $DTO): InventoryOperation
    {
        $document = DB::transaction(function () use ($DTO) {

            $document = InventoryOperation::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $DTO->date,
                'organization_id' => $DTO->organization_id,
                'storage_id' => $DTO->storage_id,
                'author_id' => Auth::id(),
                'comment' => $DTO->comment,
                'status' => $DTO->status,
                'currency_id' => $DTO->currency_id
            ]);

            GoodDocument::insert($this->insertGoodDocuments($DTO->goods, $document));

            $this->calculateSum($document, true);

            return $document;

        });

        return $document->load(['organization', 'storage', 'author', 'currency', 'documentGoods', 'documentGoods.good']);

    }

    public function update(InventoryOperation $document, InventoryOperationDTO $DTO)
    {
        return DB::transaction(function () use ($DTO, $document) {
            $document->update([
                'doc_number' => $document->doc_number,
                'date' => $DTO->date,
                'organization_id' => $DTO->organization_id,
                'storage_id' => $DTO->storage_id,
                'comment' => $DTO->comment,
                'currency_id' => $DTO->currency_id
            ]);

            if (!is_null($DTO->goods)) {
                $this->updateGoodDocuments($DTO->goods, $document);
            }
            $this->calculateSum($document);

            $data['ids'][] = $document->id;

            if ($document->active) $this->approve($data);
        });
    }

    private function insertGoodDocuments(array $goods, InventoryOperation $document): array
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'document_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, InventoryOperation $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {

                $goodDocument = GoodDocument::where('id', $good['id'])->first();

                $goodDocument->updateQuietly([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]);

            } else {
                GoodDocument::forceCreateQuietly([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'price' => $good['price'],
                    'document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]);

            }
        }
    }

    public function changeHistory(Documentable $document)
    {
        return $document->load(['history.changes', 'history.user']);
    }

    public function deleteDocumentData(InventoryOperation $document)
    {
//        $document->goodAccountents()->delete();
//
//        $document->counterpartySettlements()->delete();
//
//        $document->balances()->delete();
    }


    public function approve(array $data)
    {
        try {
            foreach ($data['ids'] as $id) {
                $document = InventoryOperation::find($id);

                if ($document->active) {
                    $this->deleteDocumentData($document);

                    $document->update(
                        ['active' => false]
                    );
                }

                //todo find out
//                $result = $this->checkInventory($document);
//
//                $response = [];
//
//                if ($result !== null) {
//                    foreach ($result as $goods) {
//                        $good = Good::find($goods['good_id'])->name;
//
//                        $response[] = [
//                            'amount' => $goods['amount'],
//                            'good' => $good,
//                        ];
//                    }
//
//                    return $response;
//                }

                $document->update(
                    ['active' => true]
                );

//
//                DocumentApprovedEvent::dispatch($document, MovementTypes::Outcome, DocumentTypes::SaleToClient->value);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }


    public function checkInventory(InventoryOperation $document)
    {
        $incomingDate = $document->date;

        $incomingGoods = $document->documentGoods()->pluck('good_id', 'amount')->toArray();

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
            $document = InventoryOperation::find($id);

            //  $this->deleteDocumentData($document);
            $document->update(
                ['active' => false]
            );
        }
    }

    public function delete(array $data)
    {
        try {
            foreach ($data['ids'] as $id) {
                $document = InventoryOperation::find($id);

                $document->delete();

                if ($document->active) {
                    $this->deleteDocumentData($document);

                    $document->update(
                        ['active' => false]
                    );
                }
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
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


}
