<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentGoods extends Model
{
    protected $fillable = ['good_id','price','amount','sum'];

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
