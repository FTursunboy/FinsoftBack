<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChangeGoodDocumentHistory extends Model
{
    protected $fillable = ['change_history_id', 'body', 'type', 'good'];

}
