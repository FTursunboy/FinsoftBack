<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationTypePlan extends Model
{
    protected $fillable = [
        'sale_plan_id',
        'month_id',
        'operation_type_id',
        'sum',
    ];

    public function salePlan(): BelongsTo
    {
        return $this->belongsTo(SalePlan::class, 'sale_plan_id');
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public function operationType(): BelongsTo
    {
        return $this->belongsTo(OperationType::class);
    }
}
