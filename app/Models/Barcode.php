<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barcode extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['barcode', 'good_id'];

    public static function bootSoftDeletes()
    {

    }
}
