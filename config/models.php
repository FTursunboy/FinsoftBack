<?php


use App\Models\BalanceArticle;
use App\Models\CashRegister;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\OrganizationBill;
use App\Models\User;

return [
    'model_map' => [
        'storage' => \App\Models\Storage::class,
        'sender_storage' => \App\Models\Storage::class,
        'recipient_storage' => \App\Models\Storage::class,
        'counterparty' => Counterparty::class,
        'counterparty_agreement' => CounterpartyAgreement::class,
        'organization' => Organization::class,
        'employee' => Employee::class,
        'cashRegister' => CashRegister::class,
        'author' => User::class,
        'senderCashRegister' => CashRegister::class,
        'checkingAccount' => OrganizationBill::class,
        'organizationBill' => OrganizationBill::class,
        'currency' => Currency::class,
        'balance_article' => BalanceArticle::class,
        'sender_cash_register' => CashRegister::class,
    ],
];
