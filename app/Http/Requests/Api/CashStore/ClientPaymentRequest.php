<?php

namespace App\Http\Requests\Api\CashStore;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Livewire\Attributes\Rule;

class ClientPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d H:i:s'],

            'organization_id' => ['required'],
            'cash_register_id' => ['required'],
            'sum' => ['required'],
            'counterparty_id' => ['required'],
            'counterparty_agreement_id' => ['required'],
            'operation_type_id' => ['required', \Illuminate\Validation\Rule::exists('operation_types', 'id')],
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
