<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDocumentGoods extends Model
{
    protected $fillable = ['good_id', 'accounting_quantity', 'actual_quantity', 'difference', 'inventory_document_id'];
}
