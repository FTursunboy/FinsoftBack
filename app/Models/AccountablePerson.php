<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountablePerson extends Model
{
    protected $fillable = ['date','sum','currency_sum','employee_id','operation_type_id'];
}
