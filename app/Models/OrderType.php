<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $fillable = ['name'];

    const PROVIDER = 1;
    const CLIENT = 2;
}
