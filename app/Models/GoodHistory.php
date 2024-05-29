<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodHistory extends Model
{
    public function document($model, $id)
    {
        return $model::where('id', $id);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
