<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    public $timestamps = false;

    protected $table = 'operation_types';

    protected $fillable = [
        'title_ru',
        'title_en'
    ];

    const CLIENT_PAYMENT = 1;

    const WITHDRAW = 2;

    const ANOTHER_CASH_REGISTER = 3;

    const INVESTMENT = 4;

    const CREDIT_RECIEVE = 5;

    const PROVIDER_REFUND = 6;

    const ACCOUNTABLE_PERSON_REFUND = 7;

    const OTHER_INCOMES = 8;

    const OTHER_PARISHES = 9;

    const RETURN_TO_CLIENT = 10;

    const REPLENISHMENT = 11;

    const SENDING_TO_ANOTHER_CASH_REGISTER = 12;

    const RETURN_INVESTMENT = 13;

    const CREDIT_PAYMENT = 14;

    const RETURN_TO_PROVIDER = 15;

    const ADVANCE_PAYMENT_TO_ACCOUNTANT = 16;

    const OTHER_EXPENSES = 17;

    const OTHER_PAYMENTS = 18;

    const SALARY_PAYMENT = 19;
}
