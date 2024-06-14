<?php

namespace App\Services\CashStore;

use App\Enums\MovementTypes;
use App\Models\Cash;
use App\Models\CashStore;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\OrganizationBill;


class OrganizationBillService
{
    public function __construct(public CashStore $cashStore, public MovementTypes $type)
    {
    }

    public function handle(): void
    {
        $this->organizationBill();
    }

    public function organizationBill()
    {
        $currency_sum = $this->cashStore->sum;

        $currency = $this->cashStore->counterpartyAgreement ?? $this->cashStore->organizationBill;

        if ($this->cashStore->cashRegister->currency_id != $currency->currency_id) {
            $currency_sum = $this->cashStore->sum * $this->getExcangeRate();
        }

        $organizationBill = new OrganizationBill();

        Cash::create([
            'date' => $this->cashStore->date,
            'model_id' => $this->cashStore->organizationBill_id,
            'model_type' => $organizationBill->getClassName(),
            'sum' => $this->cashStore->sum,
            'currency_sum' => $currency_sum,
            'sender' => $this->cashStore->sender,
            'recipient' => $this->cashStore->recipient,
            'operation_type_id' => $this->cashStore->operationType_id,
            'type' => $this->type
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
