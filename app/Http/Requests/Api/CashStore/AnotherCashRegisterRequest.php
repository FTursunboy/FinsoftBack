<?php

namespace App\Http\Requests\Api\CashStore;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnotherCashRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'cash_register_id' => ['required', Rule::exists('cash_registers', 'id')],
            'sum' => ['required'],
            'sender_cash_register_id' => ['required', Rule::exists('cash_registers', 'id')],
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