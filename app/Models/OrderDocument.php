<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderDocument extends Model
{
    protected $fillable = ['doc_number','date','counterparty_id','counterparty_agreement_id','organization_id','shipping_date',
                'order_status_id','author_id','comment','currency_id','summa',];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
