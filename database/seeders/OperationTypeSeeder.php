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
                'title_ru' => CashOperationType::OtherExpenses,
                'title_en' => 'OtherExpenses',
                'type' => 'PKO'
            ],
            [
                'title_ru' => CashOperationType::OtherIncomes,
                'title_en' => 'OtherIncomes',
                'type' => 'PKO'
            ],


            [
                'title_ru' => 'Возврат клиенту',
                'title_en' => 'ClientReturn',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Пополнение с Р/С',
                'title_en' => 'Refill',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Отправка на другую кассу',
                'title_en' => 'AnotherCashRegister',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Возврат вложения',
                'title_en' => 'ReturnInvestment',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Оплата кредита',
                'title_en' => 'CreditPayment',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Возврат поставщику',
                'title_en' => 'RefundToProvider',
                'type' => 'RKO'
            ],
            [
                'title_ru' => 'Оплата аванс подотчетнику',
                'title_en' => 'AccountablePersonRefund',
                'type' => 'RKO'
            ],
            [
                'title_ru' => "Прочие расходы",
                'title_en' => 'OtherExpenses',
                'type' => 'RKO'
            ],
            [
                'title_ru' =>"Прочие оплаты",
                'title_en' => 'OtherPayment',
                'type' => 'RKO'
            ],
            [
                'title_ru' =>"Оплата зарплаты",
                'title_en' => 'SalaryPayment',
                'type' => 'RKO'
            ]
        ]);
    }
}
