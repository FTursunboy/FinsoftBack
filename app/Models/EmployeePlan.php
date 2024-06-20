<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePlan extends Model
{
    protected $fillable = [
        'sale_plan_id',
        'month_id',
        'employee_id',
        'sum',
    ];

    public function salePlan(): BelongsTo
    {
        return $this->belongsTo(SalePlan::class, 'sale_plan_id');
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
