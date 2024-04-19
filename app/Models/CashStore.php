<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CashStore extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'date' => 'datetime'
    ];

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


    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cashRegister_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

}
