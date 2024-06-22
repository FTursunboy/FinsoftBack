<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodPlan extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use SoftDeletes;

    protected $fillable = [
        'sale_plan_id',
        'month_id',
        'good_id',
        'quantity',
    ];

    public static function bootSoftDeletes() { }

    public function goodSalePlan(): BelongsTo
    {
        return $this->belongsTo(SalePlan::class, 'sale_plan_id');
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
