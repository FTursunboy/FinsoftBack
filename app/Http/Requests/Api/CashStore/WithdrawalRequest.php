<?php

namespace App\Http\Requests\Api\CashStore;

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
            'cashRegister_id' => ['required', Rule::exists('cash_registers', 'id')],
            'sum' => ['required'],
            'organizationBill_id' => ['required', Rule::exists('organization_bills', 'id')],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'type_operation' => ['required', Rule::enum(CashOperationType::class)],
            'type' => ['required', 'string', Rule::in(['RKO', 'PKO'])]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
