<?php

namespace App\Http\Requests\Api\CheckingAccount;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnotherCashRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:d.m.Y H:i:s'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'checking_account_id' => ['required', Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'sender_cash_register_id' => ['required', Rule::exists('cash_registers', 'id')],
            'operation_type_id' => ['required', Rule::exists('operation_types', 'id')],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'type' => ['required', 'string', \Illuminate\Validation\Rule::in(['RKO', 'PKO'])]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
