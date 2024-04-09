<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDocument extends Model
{
    protected $fillable = ['doc_number','date','counterparty_id','counterparty_agreement_id','organization_id','shipping_date',
                'order_status_id','author_id','comment','currency_id','summa',];
}
