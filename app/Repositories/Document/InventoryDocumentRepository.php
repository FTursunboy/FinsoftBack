<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\InventoryDocumentDTO;
use App\DTO\Document\InventoryDocumentUpdateDTO;
use App\Models\InventoryDocument;
use App\Models\InventoryDocumentGoods;
use App\Repositories\Contracts\InventoryDocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryDocumentRepository implements InventoryDocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait;

    public $model = InventoryDocument::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['organization', 'storage', 'author', 'responsiblePerson'])->paginate($filteredParams['itemsPerPage']);
    }

    public function store(InventoryDocumentDTO $DTO): InventoryDocument
    {
        return DB::transaction(function () use ($DTO) {
            $document = $this->model::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $DTO->date,
                'organization_id' => $DTO->organization_id,
                'author_id' => Auth::id(),
                'comment' => $DTO->comment,
                'responsible_person_id' => $DTO->responsible_person_id,
                'storage_id' => $DTO->storage_id,
            ]);

            if (!is_null($DTO->goods))
                InventoryDocumentGoods::insert($this->insertGoodDocuments($DTO->goods, $document));

            return $document->load(['organization', 'author', 'storage', 'responsiblePerson', 'inventoryDocumentGoods']);
        });
    }

    public function update(InventoryDocument $document, InventoryDocumentUpdateDTO $DTO): InventoryDocument
    {
        return DB::transaction(function () use ($DTO, $document) {
            $document->update([
                'doc_number' => $document->doc_number,
                'date' => $DTO->date,
                'organization_id' => $DTO->organization_id,
                'comment' => $DTO->comment,
                'responsible_person_id' => $DTO->responsible_person_id,
                'storage_id' => $DTO->storage_id,
            ]);

            if (!is_null($DTO->goods)) {
                $this->updateGoodDocuments($DTO->goods, $document);
            }

            return $document->load(['organization', 'author', 'storage', 'responsiblePerson', 'inventoryDocumentGoods']);
        });
    }


    private function insertGoodDocuments(array $goods, InventoryDocument $document)
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'accounting_quantity' => $item['accounting_quantity'],
                'actual_quantity' => $item['actual_quantity'],
                'difference' => $item['difference'],
                'inventory_document_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, InventoryDocument $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {
                InventoryDocumentGoods::updateOrCreate(
                    ['id' => $good['id']],
                    [
                        'good_id' => $good['good_id'],
                        'accounting_quantity' => $good['accounting_quantity'],
                        'actual_quantity' => $good['actual_quantity'],
                        'difference' => $good['difference'],
                        'inventory_document_id' => $document->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
            } else {
                InventoryDocumentGoods::create([
                    'good_id' => $good['good_id'],
                    'accounting_quantity' => $good['accounting_quantity'],
                    'actual_quantity' => $good['actual_quantity'],
                    'difference' => $good['difference'],
                    'inventory_document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO)
    {
        InventoryDocumentGoods::whereIn('id', $DTO->ids)->delete();
    }
}
