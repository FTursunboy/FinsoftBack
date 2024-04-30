<?php

namespace App\Http\Requests\Api\CheckingAccount;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'checking_account_id' => ['required', Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'organization_bill_id' => ['required', Rule::exists('organization_bills', 'id')],
            'operation_type_id' => ['required', Rule::exists('operation_types', 'id')],
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
