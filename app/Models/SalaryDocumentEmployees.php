<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryDocumentEmployees extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'oklad',
        'worked_hours',
        'salary',
        'another_payments',
        'takes_from_salary',
        'payed_salary',
        'salary_document_id',
        'employee_id'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
