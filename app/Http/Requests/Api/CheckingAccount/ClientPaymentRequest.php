<?php

namespace App\Http\Requests\Api\CheckingAccount;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Livewire\Attributes\Rule;

class ClientPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', \Illuminate\Validation\Rule::exists('organizations', 'id')],
            'checking_account_id' => ['required', \Illuminate\Validation\Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'counterparty_id' => ['required', \Illuminate\Validation\Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required'],
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
