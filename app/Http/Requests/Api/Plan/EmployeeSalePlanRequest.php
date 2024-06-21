<?php

namespace App\Http\Requests\Api\Plan;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'employees' => ['required', 'array'],
            'employees.*.employee_id' => ['required', 'integer', 'exists:employees,id'],
            'employees.*.month_id' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
