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
                'title_en' => 'ClientPayment',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::WithDraw,
                'title_en' => 'Withdraw',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::AnotherCashRegister,
                'title_en' => 'AnotherCashRegister',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::Investment,
                'title_en' => 'Investment',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::CreditReceive,
                'title_en' => 'CreditReceive',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::ProviderRefund,
                'title_en' => 'ProviderRefund',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::AccountablePersonRefund,
                'title_en' => 'AccountablePersonRefund',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::OtherIncomes,
                'title_en' => 'OtherIncomes',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::OtherParishes,
                'title_en' => 'OtherParishes',
                'type' => 'PKO'
            ],


            [
                'title_ru' => CashOperationType::ReturnToClient,
                'title_en' => 'ReturnToClient',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::Replenishment,
                'title_en' => 'Replenishment',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::SendingToAnotherCashRegister,
                'title_en' => 'SendingToAnotherCashRegister',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::ReturnInvestment,
                'title_en' => 'ReturnInvestment',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::CreditPayment,
                'title_en' => 'CreditPayment',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::ReturnToProvider,
                'title_en' => 'ReturnToProvider',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::AdvancePaymentToAccountant,
                'title_en' => 'AdvancePaymentToAccountant',
                'type' => 'RKO'
            ],
            [
                'title_ru' => CashOperationType::OtherExpenses,
                'title_en' => 'OtherExpenses',
                'type' => 'RKO'
            ],
            [
                'title_ru' =>CashOperationType::OtherPayments,
                'title_en' => 'OtherPayments',
                'type' => 'RKO'
            ],

            [
                'title_ru' =>CashOperationType::SalaryPayment,
                'title_en' => 'SalaryPayment',
                'type' => 'RKO'
            ]
        ]);
    }
}
