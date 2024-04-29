<?php

namespace App\Http\Requests\Api\ReportCard;

use Illuminate\Foundation\Http\FormRequest;

class ReportCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => 'required|integer|exists:organizations,id',
            'month_id' => 'required|integer|exists:months,id',
            'date' => ['required'],
            'comment' => ['nullable'],
            'data' => ['array', 'required'],
            'data.*.employee_id' => ['exists:employees,id', 'integer', 'required'],
            'data.*.standart_hours' => ['integer', 'required'],
            'data.*.fact_hours' => ['integer', 'required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
