<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Category extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $guarded = false;

    public static function bootSoftDeletes()
    {

    }

}
