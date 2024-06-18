<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceGoods extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'good_id',
        'service_id',
        'type',
        'price',
        'amount',
    ];
}
