<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentGoods extends Model
{
    protected $fillable = ['good_id','price','amount','sum'];
}
