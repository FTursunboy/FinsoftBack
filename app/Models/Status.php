<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $guarded = false;

    const PROVIDER_PURCHASE = 1;

    const PROVIDER_RETURN = 2;

    const CLIENT_PURCHASE = 3;

    const CLIENT_RETURN = 4;
}
