<?php

namespace App\Repositories;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\DTO\InventoryDocumentDTO;
use App\DTO\MovementDocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Enums\DocumentHistoryStatuses;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\Good;
use App\Models\GoodDocument;
use App\Models\InventoryDocument;
use App\Models\InventoryDocumentGoods;
use App\Models\MovementDocument;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Models\OrderType;
use App\Models\Status;
use App\Models\User;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\InventoryDocumentRepositoryInterface;
use App\Repositories\Contracts\MovementDocumentRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class InventoryDocumentRepository implements InventoryDocumentRepositoryInterface
{
    use FilterTrait, Sort;

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

    public function update(InventoryDocument $document, InventoryDocumentDTO $DTO): InventoryDocument
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
                InventoryDocumentGoods::query()->updateOrInsert(...$this->insertGoodDocuments($DTO->goods, $document));
            }

            return $document->load(['organization', 'author', 'storage', 'responsiblePerson', 'inventoryDocumentGoods']);
        });
    }

    public function uniqueNumber(): string
    {
        $lastRecord = $this->model::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

    private function insertGoodDocuments(array $goods, InventoryDocument $document): array
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
}
