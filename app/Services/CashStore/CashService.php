<?php

namespace App\Services\CashStore;

use App\Enums\MovementTypes;
use App\Models\Cash;
use App\Models\CashRegister;
use App\Models\CashStore;
use App\Models\Currency;
use App\Models\ExchangeRate;


class CashService
{
    public function __construct(public CashStore $cashStore, public MovementTypes $type) { }

    public function handle(): void
    {
        $this->cash();
    }

    public function cash()
    {
        $currency_sum = $this->cashStore->sum;

        $currency = $this->cashStore->counterpartyAgreement ?? $this->cashStore->organizationBill;

        if ($this->cashStore->cashRegister->currency_id != $currency->currency_id) {
            $currency_sum = $this->cashStore->sum * $this->getExcangeRate();
        }

        $cashRegister = new CashRegister();

        Cash::create([
            'date' => $this->cashStore->date,
            'model_id' => $this->cashStore->cashRegister_id,
            'model_type' => $cashRegister->getClassName(),
            'sum' => $this->cashStore->sum,
            'currency_sum' => $currency_sum,
            'sender' => $this->cashStore->sender,
            'recipient' => $this->cashStore->recipient,
            'operation_type_id' => $this->cashStore->operationType_id,
            'type' => $this->type,
            'organization_id' => $this->cashStore->organization_id,
        ]);
    }

    public function getCurrency()
    {
        $currency = $this->cashStore->counterpartyAgreement ?? $this->cashStore->organizationBill;

        return Currency::where('id', $currency->currency_id)->first()->id;
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
