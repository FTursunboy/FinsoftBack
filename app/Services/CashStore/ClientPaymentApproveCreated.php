<?php

namespace App\Services\CashStore;

use App\Models\Cash;
use App\Models\CashStore;
use App\Models\Currency;
use App\Models\ExchangeRate;


class ClientPaymentApproveCreated
{
    public function __construct(public CashStore $cashStore) { }

    public function handle(): void
    {
        $this->cash();
    }

    public function cash()
    {
        if ($this->cashStore->currency_id !== $this->getDefaultCurrency()) {
            $currency_sum = $this->cashStore->sale_sum * $this->getExcangeRate();
        }

        Cash::create([
            'date' => $this->cashStore->date,
            'model_id' => $this->cashStore->id,
            'model_type' => get_class($this->cashStore),
            'sum' => $this->cashStore->sum,
            'sender' => $this->cashStore->sender,
            'recipient' => $this->cashStore->recipient,
            'operation_type_id' => $this->cashStore->operation_type_id
        ]);
    }



    public function getCurrency()
    {
        return Currency::where('id', $this->cashStore->currency_id)->first()->id;
    }

    public function getDefaultCurrency()
    {
        return Currency::default()->first()->id;
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
