<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'name',
        'next_payment',
        'last_payment',
        'has_access',
        'mobile_access'
    ];

    protected $casts = [
        'last_payment' => 'datetime',
        'next_payment' => 'datetime',
        'has_access' => 'boolean',
        'mobile_access' => 'boolean'
    ];


}
