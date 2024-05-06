<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodAccounting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'movement_type',
        'amount',
        'sum',
        'model_id',
        'active',
        'date'
    ];

    protected function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    protected function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }

    protected function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
