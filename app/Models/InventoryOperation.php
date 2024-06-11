<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryOperation extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'doc_number',
        'status_id',
        'active',
        'organization_id',
        'storage_id',
        'author_id',
        'date',
        'comment',
        'currency_id',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
