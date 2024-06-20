<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodPlan extends Model
{
    protected $fillable = [
        'good_sale_plan_id',
        'month_id',
        'good_id',
        'quantity',
    ];

    public function goodSalePlan(): BelongsTo
    {
        return $this->belongsTo(GoodSalePlan::class);
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }
}
