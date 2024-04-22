<?php

namespace App\Http\Requests\Api\Hiring;

use Illuminate\Foundation\Http\FormRequest;

class HiringRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'salary' => ['required', 'decimal:1,10'],
            'hiring_date' => ['required', 'date'],
            'department_id' => ['required', 'exists:departments,id'],
            'basis' => ['required'],
            'position_id' => ['required', 'exists:positions,id'],
            'organization_id' => ['required', 'exists:organizations,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
