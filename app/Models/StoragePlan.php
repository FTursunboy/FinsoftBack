<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoragePlan extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use SoftDeletes;

    protected $fillable = [
        'sale_plan_id',
        'month_id',
        'storage_id',
        'sum',
        'deleted_at'
    ];

    public static function bootSoftDeletes() { }

    public function salePlan(): BelongsTo
    {
        return $this->belongsTo(SalePlan::class, 'sale_plan_id');
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }
}
