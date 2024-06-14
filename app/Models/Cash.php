<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $fillable = ['date', 'model_id', 'model_type', 'sum', 'currency_sum', 'sender', 'recipient', 'operation_type_id', 'type'];
}
