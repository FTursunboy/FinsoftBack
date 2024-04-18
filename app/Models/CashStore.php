<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CashStore extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'cashRegister_id',
        'sum',
        'counterparty_id',
        'counterparty_agreement_id',
        'basis',
        'comment',
        'author_id',
        'organizationBill_id',
        'senderCashRegister_id',
        'employee_id',
        'balanceKey_id',
        'operation_type',
        'type'
    ];


}
