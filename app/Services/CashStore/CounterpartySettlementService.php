<?php

namespace App\Services\CashStore;

use App\Enums\MovementTypes;
use App\Models\Cash;
use App\Models\CashStore;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\ExchangeRate;


class CounterpartySettlementService
{
    public function __construct(public CashStore $cashStore, public MovementTypes $type) { }

    public function handle(): void
    {
        $this->counterpartySettlement();
    }

    private function counterpartySettlement(): void
    {
        $currency_sum = $this->cashStore->sum;

        if ($this->cashStore->cashRegister->currency_id != $this->cashStore->counterpartyAgreement->currency_id) {
            $currency_sum = $this->cashStore->sum * $this->getExchangeRate();
        }

        CounterpartySettlement::create([
            'counterparty_id' => $this->cashStore->counterparty_id,
            'counterparty_agreement_id' => $this->cashStore->counterparty_agreement_id,
            'organization_id' => $this->cashStore->organization_id,
            'date' => $this->cashStore->date,
            'movement_type' => $this->type,
            'currency_id' => $this->cashStore->cashRegister->currency_id,
            'model_id' => $this->cashStore->id,
            'sum' => $this->cashStore->sum ?? 0,
            'sale_sum' => $currency_sum ?? 0,
            'active' => true
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
