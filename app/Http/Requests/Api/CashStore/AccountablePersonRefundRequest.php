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
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'cash_register_id' => ['required', Rule::exists('cash_registers', 'id')],
            'sum' => ['required'],
            'employee_id' => ['required', Rule::exists('employees', 'id')],
            'operation_type_id' => ['required', Rule::exists('operation_types', 'id')],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'type' => ['required', 'string', Rule::in(['RKO', 'PKO'])],
            'sender' => ['required_if:type,PKO', 'string'],
            'recipient' => ['required_if:type,PKO', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
