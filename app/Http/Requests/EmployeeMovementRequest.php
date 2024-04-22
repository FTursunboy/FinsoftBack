<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeMovementRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'doc_number' => ['required'],
            'date' => ['required'],
            'employee_id' => ['required', 'integer'],
            'salary' => ['required', 'numeric'],
            'position' => ['required', 'integer'],
            'movement_date' => ['required'],
            'schedule' => ['nullable'],
            'basis' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
