<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseLogs extends Model
{
    protected $fillable = [
        'status',
        'data',
        'notification_id',
        'user_id',
        'type',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
