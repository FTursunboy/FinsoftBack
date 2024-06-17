<?php

namespace App\Services\CashStore;

use App\Enums\MovementTypes;
use App\Models\Balance;
use App\Models\BalanceArticle;
use App\Models\Cash;
use App\Models\CashStore;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\ExchangeRate;


class BalanceService
{
    public function __construct(public CashStore $cashStore, public MovementTypes $type) { }

    public function handle(): void
    {
        $this->balance();
    }

    private function balance(): void
    {
        $currency_sum = $this->cashStore->sum;

        if ($this->cashStore->cashRegister->currency_id != $this->cashStore->counterpartyAgreement->currency_id) {
            $currency_sum = $this->cashStore->sum * $this->getExchangeRate();
        }

        Balance::create([
            'credit_article' => BalanceArticle::find(1)->id,
            'debit_article' => BalanceArticle::find(2)->id,
            'organization_id' => $this->cashStore->organization_id,
            'sum' => $currency_sum,
            'model_id' => $this->cashStore->cashRegister_id,
            'active' => true,
            'date' => $this->cashStore->date,
        ]);
    }


    private function getCurrency()
    {
        return Currency::where('id', $this->cashStore->cashRegister->currency_id)->first()->id;
    }

    private function getExchangeRate()
    {
        return ExchangeRate::query()
            ->where('currency_id', $this->getCurrency())
            ->orderBy('date', 'desc')
            ->first()
            ->value;
    }

}
