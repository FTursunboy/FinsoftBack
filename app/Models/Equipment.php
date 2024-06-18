<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = ['date','doc_number','organization_id','good_id','storage_id','amount'];
}
