<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\MovementDocumentDTO;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Events\MovementApprovedEvent;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\MovementDocument;
use App\Repositories\Contracts\Document\MovementDocumentRepositoryInterface;
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

        return $query->with(['sender_storage', 'recipient_storage', 'author', 'organization', 'goods', 'goods.good', 'documentGoodsWithCount'])->paginate($filteredParams['itemsPerPage']);
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

            GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));

            return $document->load(['organization', 'author', 'sender_storage', 'recipient_storage']);
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

            return $document->load(['sender_storage', 'recipient_storage', 'author', 'organization', 'goods', 'goods.good']);

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

    public function changeHistory(Document $document): Document
    {
        return $document->load(['history.changes', 'history.user']);
    }


    public function documentAuthor()
    {
        return null;
    }

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO)
    {
        GoodDocument::whereIn('id', $DTO->ids)->delete();
    }

    public function approve(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = $this->model::find($id);

            $result = $this->checkInventory($document);

            $response = [];

            if ($result !== null) {
                foreach ($result as $goods) {
                    $good = Good::find($goods['good_id'])->name;

                    $response[] = [
                        'amount' => $goods['amount'],
                        'good' => $good,
                    ];
                }

                return $response;
            }


            $document->update(
                ['active' => true]
            );

            MovementApprovedEvent::dispatch($document, MovementTypes::Outcome, DocumentTypes::Movement->value, $document->sender_storage_id);
            MovementApprovedEvent::dispatch($document, MovementTypes::Income, DocumentTypes::Movement->value, $document->recipient_storage_id);
        }
    }

    public function checkInventory(MovementDocument $document)
    {
        $incomingDate = $document->date;

        $incomingGoods = $document->documentGoods->pluck('good_id', 'amount')->toArray();

        $previousIncomings = GoodAccounting::where('movement_type', MovementTypes::Income)
            ->where('storage_id', $document->sender_storage_id)
            ->where('date', '<=', $incomingDate)
            ->get();

        $previousOutgoings = GoodAccounting::where('movement_type', MovementTypes::Outcome)
            ->where('date', '<=', $incomingDate)
            ->where('storage_id', $document->sender_storage_id)
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
            $document = $this->model::find($id);

            $document->update(
                ['active' => false]
            );
        }
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $document = $this->model::where('id', $id)->first();
                $document->goodAccountents()->delete();
                $document->update([
                    'deleted_at' => Carbon::now(),
                    'active' => 0
                ]);

            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
