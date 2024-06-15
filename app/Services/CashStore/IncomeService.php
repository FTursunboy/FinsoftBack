<?php

namespace App\Services\CashStore;

use App\Enums\MovementTypes;
use App\Models\Cash;
use App\Models\CashRegister;
use App\Models\CashStore;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Income;


class IncomeService
{
    public function __construct(public CashStore $cashStore, public MovementTypes $type) { }

    public function handle(): void
    {
        $this->cash();
    }

    public function cash()
    {
        $cashRegister = new CashRegister();

        Income::create([
            'date' => $this->cashStore->date,
            'model_id' => $this->cashStore->cashRegister_id,
            'model_type' => $cashRegister->getClassName(),
            'sum' => $this->cashStore->sum,
            'organization_id' => $this->cashStore->organization_id,
            'balance_article_id' => $this->cashStore->balance_article_id,
            'type' => $this->type
        ]);
    }

}
