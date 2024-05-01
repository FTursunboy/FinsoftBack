<?php

namespace App\Services;

use App\Enums\MovementTypes;
use App\Models\Balance;
use App\Models\BalanceArticle;
use App\Models\CounterpartySettlement;
use App\Models\DocumentModel;
use App\Models\GoodAccounting;
use App\Repositories\Contracts\Documentable;

class HandleDocumentCreated
{
    public function __construct(public DocumentModel $document, public MovementTypes $type)
    {

    }

    public function handle(): void
    {
        $this->counterpartySettlement();
        $this->goodAccounting();
        $this->balance();
    }

    private function counterpartySettlement(): void
    {
        CounterpartySettlement::create([
            'counterparty_id' => $this->document->counterparty_id,
            'counterparty_agreement_id' => $this->document->counterparty_agreement_id,
            'organization_id' => $this->document->organization_id,
            'movement_type' => $this->type,
            'model_id' => $this->document->id,
            'sum' => 10,
            'sale_sum' => 100,
            'active' => false
        ]);
    }

    private function goodAccounting(): void
    {
        $goods = $this->document->documentGoods;

        $insertData = [];

        foreach ($goods as $good) {
            $insertData[] = [
                'good_id' => $good->good_id,
                'sum' => $this->document->saleInteger,
                'model_id' => $good->document_id,
                'created_at' => now(),
                'storage_id' => $this->document->storage_id,
                'movement_type' => $this->type,
                'organization_id' => $this->document->organization_id,
                'amount' => $good->amount,
                'active' => false
            ];
        }

        GoodAccounting::insert($insertData);
    }

    private function balance(): void
    {
        Balance::create([
            'credit_article' => BalanceArticle::find(1)->id,
            'debit_article' => BalanceArticle::find(1)->id,
            'organization_id' => $this->document->organization_id,
            'sum' => $this->document->totalGoodsSum->first()->total_sum,
            'model_id' => $this->document->id,
            'active' => false
        ]);
    }


    private function getSumByCurrency(): float
    {
        return 1;
    }
}
