<?php

namespace App\Http\Requests\Api\EmployeeMovement;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeMovementRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'salary' => ['required', 'decimal:1,5'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'movement_date' => ['required', 'date'],
            'schedule' => ['nullable'],
            'basis' => ['nullable', 'string'],
            'department_id' => ['int', 'exists:departments,id', 'required'],
            'organization_id' => ['required', 'int', 'exists:organizations,id'],
            'comment' => ['nullable', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}