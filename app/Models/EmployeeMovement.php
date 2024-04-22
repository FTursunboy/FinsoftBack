<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeMovement extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'doc_number',
        'date',
        'employee_id',
        'salary',
        'position',
        'movement_date',
        'schedule',
        'basis',
    ];
}
