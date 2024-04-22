<?php

namespace App\Http\Requests\Api\CashStore;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountablePersonRefundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'organization_bill_id' => ['required', Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'employee_id' => ['required', Rule::exists('employees', 'id')],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'type' => ['required', 'string', Rule::in(['RKO', 'PKO'])]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
