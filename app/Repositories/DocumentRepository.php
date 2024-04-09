<?php

namespace App\Repositories;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\DTO\OrderDocumentDTO;
use App\Enums\DocumentHistoryStatuses;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\Good;
use App\Models\GoodDocument;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class DocumentRepository implements DocumentRepositoryInterface
{
    use FilterTrait, Sort;

    public $model = Document::class;

    public function index(int $status, array $data): LengthAwarePaginator
    {
        $filteredParams = $this->processSearchData($data);

        $query = $this->model::query()->where('status_id', $status);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'storage', 'author', 'counterparty_agreement', 'currency']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(DocumentDTO $dto, int $status): Document
    {
        return DB::transaction(function () use ($status, $dto) {
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
                'currency_id' => $dto->currency_id
            ]);

            if (!is_null($dto->goods))
                GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));


            return $document->load(['counterparty', 'organization', 'storage', 'author', 'counterparty_agreement', 'currency']);
        });

    }

    public function update(Document $document, DocumentUpdateDTO $dto): Document
    {
        return DB::transaction(function () use ($dto, $document) {
            $document->update([
                'doc_number' => $this->uniqueNumber(),
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

                GoodDocument::query()->updateOrInsert(...$this->insertGoodDocuments($dto->goods, $document));
            }

            return $document;

        });
    }

    public function order(OrderDocumentDTO $DTO)
    {
        return DB::transaction(function () use ($DTO) {
            $document = OrderDocument::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $DTO->date,
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
                OrderDocumentGoods::insert($this->orderGoods($document, $DTO->goods));

            return $document;
        });
    }

    public function uniqueNumber(): string
    {
        $lastRecord = Document::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

    private function insertGoodDocuments(array $goods, Document $document): array
    {
//        $history = DocumentHistory::create([
//            'status' => DocumentHistoryStatuses::UPDATED,
//            'user_id' => Auth::user()->id,
//            'document_id' => $document->id,
//        ]);
//
//
//        $changes = [];
//
//
//        foreach ($goods as $good){
//
//           if (!$good['created']) {
//                $changes[] = [
//                    'document_history_id' => $history->id,
//                    'body' => $this->changeCount($good, DocumentHistoryStatuses::CREATED)
//                ];
//           }
//        }


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

    public function changeCount($good, $type)
    {
        dd($good);
        return [
            'name'
        ];
    }

    public function approve(Document $document)
    {

        $document->update(
            ['active' => true]
        );
    }

    public function unApprove(Document $document)
    {

        $document->update(
            ['active' => false]
        );

    }

    public function changeHistory(Document $document): Document
    {
        return $document->load(['history.changes', 'history.user']);
    }

    public function orderGoods(OrderDocument $document, array $goods)
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'price' => $item['price'],
                'auto_sale_percent' => $item['auto_sale_percent'],
                'auto_sale_sum' => $item['auto_sale_sum'],
                'order_document_id' => $document->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $goods);
    }
}
