<?php

namespace App\Repositories\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;
use App\Models\Status;
use App\Repositories\Contracts\Document\ClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\Documentable;
use App\Traits\CalculateSum;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientDocumentRepository implements ClientDocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait, CalculateSum;

    public $model = Document::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::query()->where('status_id', Status::CLIENT_PURCHASE);

        $query = $this->sort($filteredParams, $query, ['counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoodsWithCount', 'totalGoodsSum']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(DocumentDTO $dto): Document
    {

        $document = DB::transaction(function () use ($dto) {

            $document = Document::create([
                'doc_number' => $this->uniqueNumber(),
                'date' => $dto->date,
                'counterparty_id' => $dto->counterparty_id,
                'counterparty_agreement_id' => $dto->counterparty_agreement_id,
                'organization_id' => $dto->organization_id,
                'storage_id' => $dto->storage_id,
                'author_id' => Auth::id(),
                'comment' => $dto->comment,
                'status_id' => Status::CLIENT_PURCHASE,
                'saleInteger' => $dto->saleInteger,
                'salePercent' => $dto->salePercent,
                'currency_id' => $dto->currency_id
            ]);


            GoodDocument::insert($this->insertGoodDocuments($dto->goods, $document));

            $this->calculateSum($document, true);
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
                'salePercent' => $dto->salePercent,
                'saleInteger' => $dto->saleInteger,
                'currency_id' => $dto->currency_id
            ]);

            if (!is_null($dto->goods)) {
                $this->updateGoodDocuments($dto->goods, $document);
            }

            $this->calculateSum($document);

        });
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
                        'auto_sale_percent' => $good['auto_sale_percent'] ?? null,
                        'document_id' => $document->id,
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


    public function changeHistory(Documentable $document)
    {
        return $document->load(['history.changes', 'history.user']);
    }

    public function deleteDocumentData(Document $document)
    {
        $document->goodAccountents()->delete();
        $document->counterpartySettlements()->delete();
        $document->balances()->delete();
    }


    public function approve(array $data)
    {
        return DB::transaction(function () use ($data) {
            foreach ($data['ids'] as $id) {
                $document = Document::find($id);

                $result = $this->checkInventory($document);

                $response = [];

                if ($result !== null) {
                    foreach ($result as $goods) {
                        $good = Good::find($goods['good_id'])->name;

                        $response[] = [
                            'good' => $good,
                            'amount' => $goods['amount']
                        ];
                    }

                    return $response;
                }


                if ($document->active) {
                    $this->deleteDocumentData($document);
                }

                $document->update(
                    ['active' => true]
                );

                DocumentApprovedEvent::dispatch($document, MovementTypes::Outcome);
            }
        });
    }


    public function checkInventory(Document $document)
    {
        $incomingDate = $document->date;

        $incomingGoods = $document->documentGoods->pluck('good_id', 'amount')->toArray();

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
            $document = Document::find($id);

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

            foreach ($ids as $id) {
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
}
