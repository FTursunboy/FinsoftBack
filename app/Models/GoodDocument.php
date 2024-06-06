<?php

namespace App\Models;

use App\Enums\DocumentHistoryStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodDocument extends Model
{
    use SoftDeletes;

    protected $fillable = ['good_id', 'amount', 'price', 'document_id', 'deleted_at', 'auto_sale_percent', 'auto_sale_sum'];

    protected $casts = [
        'price' => 'float'
    ];
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

}

