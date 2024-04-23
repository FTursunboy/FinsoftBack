<?php

namespace App\Http\Requests\Api\Firing;

use Illuminate\Foundation\Http\FormRequest;

class FiringRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'firing_date' => ['required', 'date'],
            'basis' => ['required', 'string'],
            'comment' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
