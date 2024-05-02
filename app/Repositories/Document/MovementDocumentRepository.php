<?php

namespace App\Repositories\Document;

use App\DTO\Document\MovementDocumentDTO;
use App\Models\Document;
use App\Models\GoodDocument;
use App\Models\MovementDocument;
use App\Repositories\Contracts\MovementDocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovementDocumentRepository implements MovementDocumentRepositoryInterface
{
    public $model = MovementDocument::class;

    use DocNumberTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['senderStorage', 'recipientStorage', 'author', 'organization', 'goods', 'goods.good', 'documentGoodsWithCount'])->paginate($filteredParams['itemsPerPage']);
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
                'recipient_storage_id' => $dto->recipient_storage_id
            ]);

            if (!is_null($dto->goods))
                GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));


            return $document->load(['organization', 'author', 'senderStorage', 'recipientStorage']);
        });
    }

    public function update(MovementDocument $document, MovementDocumentDTO $dto): MovementDocument
    {
        return DB::transaction(function () use ($dto, $document) {
            $document->update([
                'date' => $dto->date,
                'organization_id' => $dto->organization_id,
                'comment' => $dto->comment,
                'sender_storage_id' => $dto->sender_storage_id,
                'recipient_storage_id' => $dto->recipient_storage_id
            ]);

                if ($dto->goods != null)
                    $this->updateGoodDocuments($dto->goods, $document);

            return $document->load(['senderStorage', 'recipientStorage', 'author', 'organization', 'goods', 'goods.good']);

        });
    }

    private function insertGoodDocuments(array $goods, MovementDocument $document): array
    {
        return array_map(function ($item) use ($document) {
            return [
                'good_id' => $item['good_id'],
                'amount' => $item['amount'],
                'document_id' => $document->id,
                'created_at' => Carbon::now()
            ];
        }, $goods);
    }

    private function updateGoodDocuments(array $goods, MovementDocument $document)
    {
        foreach ($goods as $good) {
            if (isset($good['id'])) {
                GoodDocument::updateOrCreate(
                    ['id' => $good['id']],
                    [
                        'good_id' => $good['good_id'],
                        'amount' => $good['amount'],
                        'document_id' => $document->id,
                        'updated_at' => Carbon::now()
                    ]
                );
            } else {
                GoodDocument::create([
                    'good_id' => $good['good_id'],
                    'amount' => $good['amount'],
                    'document_id' => $document->id,
                    'updated_at' => Carbon::now()
                ]);
            }
        }
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


    public function documentAuthor()
    {
         return null;
    }
}
