<?php

namespace App\Http\Requests\Api\SalaryDocument;

use Illuminate\Foundation\Http\FormRequest;

class SalaryDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:d.m.Y H:i:s'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'month_id' => ['required', 'integer', 'exists:months,id'],
            'comment' => ['nullable'],
            'data' => ['required', 'array'],
            'data.*.id'  => ['nullable'],
            'data.*.employee_id'  => ['required', 'integer', 'exists:employees,id'],
            'data.*.oklad'  => ['required', 'numeric'],
            'data.*.worked_hours'  => ['required', 'integer'],
            'data.*.salary'  => ['required', 'numeric'],
            'data.*.another_payments'  => ['required', 'numeric'],
            'data.*.takes_from_salary'  => ['required', 'numeric'],
            'data.*.payed_salary'  => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
