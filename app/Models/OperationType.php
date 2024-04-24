<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    public $timestamps = false;

    protected $table = 'operation_types';

    protected $fillable = [
        'title_ru',
        'title_en'
    ];
}
