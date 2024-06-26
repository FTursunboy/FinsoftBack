<?php

namespace App\Http\Requests\Api\Plan;

use Illuminate\Foundation\Http\FormRequest;

class OperationTypeSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'operationTypes' => ['required', 'array'],
            'operationTypes.*.employee_id' => ['required', 'integer', 'exists:operation_types,id'],
            'operationTypes.*.month_id' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
