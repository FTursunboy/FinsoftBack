<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDocumentGoods extends Model
{
    protected $fillable = ['good_id','amount','price','order_document_id','auto_sale_percent','auto_sale_sum'];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Good::class,'good_id');
    }
}
