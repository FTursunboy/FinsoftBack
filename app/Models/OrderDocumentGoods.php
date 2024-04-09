<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDocumentGoods extends Model
{
    protected $fillable = ['good_id','amount','price','order_document_id','auto_sale_percent','auto_sale_sum'];
}
