<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
    ];
}
