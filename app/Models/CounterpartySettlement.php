<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounterpartySettlement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'movement_type',
        'sale_sum',
        'sum',
        'model_id',
        'active',
        'counterparty_id',
        'counterparty_agreement_id',
        'organization_id'
    ];

    protected function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    protected function counterpartyAgreement(): BelongsTo
    {
        return $this->belongsTo(CounterpartyAgreement::class);
    }

    protected function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
