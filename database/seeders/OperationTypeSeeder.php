<?php

namespace Database\Seeders;

use App\Enums\CashOperationType;
use App\Models\OperationType;
use Illuminate\Database\Seeder;

class OperationTypeSeeder extends Seeder
{
    public function run(): void
    {
        OperationType::insert([
            [
                'title_ru' => CashOperationType::ClientPayment,
                'title_en' => 'ClientPayment'
            ],
            [
                'title_ru' => CashOperationType::WithDraw,
                'title_en' => 'Withdraw'
            ],
            [
                'title_ru' => CashOperationType::AnotherCashRegister,
                'title_en' => 'AnotherCashRegister'
            ],
            [
                'title_ru' => CashOperationType::Investment,
                'title_en' => 'Investment'
            ],
            [
                'title_ru' => CashOperationType::CreditReceive,
                'title_en' => 'CreditReceive'
            ],
            [
                'title_ru' => CashOperationType::ProviderRefund,
                'title_en' => 'ProviderRefund'
            ],
            [
                'title_ru' => CashOperationType::AccountablePersonRefund,
                'title_en' => 'AccountablePersonRefund'
            ],
            [
                'title_ru' => CashOperationType::OtherExpenses,
                'title_en' => 'OtherExpenses'
            ],
            [
                'title_ru' => CashOperationType::OtherIncomes,
                'title_en' => 'OtherIncomes'
            ]
        ]);
    }
}
