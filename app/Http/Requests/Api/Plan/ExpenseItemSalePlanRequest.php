<?php

namespace App\Http\Requests\Api\Plan;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseItemSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'expenseItems' => ['required', 'array'],
            'expenseItems.*.employee_id' => ['required', 'integer', 'exists:expense_items,id'],
            'expenseItems.*.month_id' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
