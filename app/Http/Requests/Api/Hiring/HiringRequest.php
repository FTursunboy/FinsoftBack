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
            'salary' => ['numeric',  'required', 'between:1, 9999999.9999'],
            'hiring_date' => ['required', 'date'],
            'department_id' => ['required', 'exists:departments,id'],
            'basis' => ['required'],
            'position_id' => ['required', 'exists:positions,id'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'comment' => ['nullable', 'string'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
