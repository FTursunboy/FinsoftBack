<?php

namespace App\Http\Requests;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Livewire\Attributes\Rule;

class ClientPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required'],
            'cashRegister_id' => ['required'],
            'sum' => ['required'],
            'counterparty_id' => ['required'],
            'counterparty_agreement_id' => ['required'],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'type_operation' => ['required', \Illuminate\Validation\Rule::enum(CashOperationType::class)],
            'type' => ['required', 'in:RKO|PKO']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
