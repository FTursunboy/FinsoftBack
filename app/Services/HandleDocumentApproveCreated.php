<?php

namespace App\Services;

use App\Enums\MovementTypes;
use App\Models\Balance;
use App\Models\BalanceArticle;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\DocumentModel;
use App\Models\ExchangeRate;
use App\Models\GoodAccounting;


class HandleDocumentApproveCreated
{
    public function __construct(public DocumentModel $document, public MovementTypes $type) { }

    public function handle(): void
    {
        $this->counterpartySettlement();
        $this->goodAccounting();
        $this->balance();
    }

    private function counterpartySettlement(): void
    {
        $sum = $this->document->sum ?? 0;

        if ($this->document->currency_id !== $this->getCurrency()) {
             $sum = $this->document->sum * $this->getExcangeRate();
        }

        CounterpartySettlement::create([
            'counterparty_id' => $this->document->counterparty_id,
            'counterparty_agreement_id' => $this->document->counterparty_agreement_id,
            'organization_id' => $this->document->organization_id,
            'movement_type' => $this->type,
            'date' => $this->document->date,
            'model_id' => $this->document->id,
            'sale_sum' => $this->document->sum ?? 0,
            'sum' => $sum,
            'active' => true
        ]);
    }

    private function goodAccounting(): void
    {
        $goods = $this->document->documentGoods;

        $insertData = [];

        foreach ($goods as $good) {
            $sum = $good->amount * $good->price * $this->getExcangeRate();

            $insertData[] = [
                'good_id' => $good->good_id,
                'sum' => $sum,
                'model_id' => $good->document_id,
                'created_at' => now(),
                'storage_id' => $this->document->storage_id,
                'movement_type' => $this->type,
                'organization_id' => $this->document->organization_id,
                'amount' => $good->amount,
                'active' => true,
                'date' => $this->document->date
            ];
        }

        GoodAccounting::insert($insertData);
    }

    private function balance(): void
    {
        $sum = $this->document->sum ?? 0;

        if ($this->document->currency_id !== $this->getCurrency()) {
            $sum = $this->document->sum * $this->getExcangeRate();
        }

        Balance::create([
            'credit_article' => BalanceArticle::find(1)->id,
            'debit_article' => BalanceArticle::find(1)->id,
            'organization_id' => $this->document->organization_id,
            'sum' => $sum,
            'model_id' => $this->document->id,
            'active' => true,
            'date' => $this->document->date,
        ]);
    }

    public function getCurrency()
    {
        return Currency::where('default', true)->first()->id;
    }

    public function getExcangeRate()
    {
        return ExchangeRate::query()
            ->where('currency_id', $this->getCurrency())
            ->orderBy('date', 'desc')
            ->first()
            ->value;
    }
}
