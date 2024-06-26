<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetUpPrice extends Model
{
    protected $table = 'setup_goods';

    protected $fillable = [
        'good_id',
        'price_type_id',
        'old_price',
        'new_price',
    ];

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }
}
