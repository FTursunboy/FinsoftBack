<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OldNewClientPlan extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use SoftDeletes;

    protected $fillable = [
        'sale_plan_id',
        'month_id',
        'new_client',
        'old_client',
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

}
