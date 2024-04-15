<?php

namespace App\Repositories;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\DTO\MovementDocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Enums\DocumentHistoryStatuses;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\Good;
use App\Models\GoodDocument;
use App\Models\MovementDocument;
use App\Models\OrderDocument;
use App\Models\OrderDocumentGoods;
use App\Models\OrderType;
use App\Models\Status;
use App\Models\User;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\MovementDocumentRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class MovementDocumentRepository implements MovementDocumentRepositoryInterface
{
    use FilterTrait, Sort;

    public $model = MovementDocument::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        $query = $this->search($query, $data);

        $query = $this->sort($filteredParams, $query, ['organization', 'recipientStorage', 'author', 'senderStorage']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }


    public function store(MovementDocumentDTO $dto): MovementDocument
    {
        return DB::transaction(function () use ($dto) {
            $document = $this->model::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'organization_id' => $dto->organization_id,
                'author_id' => Auth::id(),
                'comment' => $dto->comment,
                'sender_storage_id' => $dto->sender_storage_id,
                'recipient_storage_id' => $dto->sender_storage_id
            ]);

            if (!is_null($dto->goods))
                GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));


            return $document->load(['organization', 'author', 'senderStorage', 'recipientStorage']);
        });
    }

    public function update(MovementDocument $document, MovementDocumentDTO $dto): Document
    {
        return DB::transaction(function () use ($dto, $document) {
            $document->update([
                'date' => $dto->date,
                'organization_id' => $dto->organization_id,
                'comment' => $dto->comment,
                'sender_storage_id' => $dto->sender_storage_id,
                'recipient_storage_id' => $dto->sender_storage_id
            ]);

            if (!is_null($dto->goods)) {
                GoodDocument::query()->updateOrInsert(...$this->insertGoodDocuments($dto->goods, $document));
            }

            return $document;

        });
    }



    public function uniqueNumber(): string
    {
        $lastRecord = MovementDocument::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }


    private function insertGoodDocuments(array $goods, MovementDocument $document): array
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
                'document_id' => $document->id,
                'created_at' => Carbon::now()
            ];
        }, $goods);
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

    public function search($query, array $data)
    {
        $search = $data['search'] ?? null;
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function documentAuthor()
    {
         return null;
    }
}
