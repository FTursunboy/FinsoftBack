<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDocumentGoods extends Model
{
    use SoftDeletes;

    protected $fillable = ['good_id', 'accounting_quantity', 'actual_quantity', 'difference', 'inventory_document_id'];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
