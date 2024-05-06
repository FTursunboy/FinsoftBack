<?php

namespace App\Http\Requests\Api\CheckingAccount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:d.m.Y H:i:s'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'checking_account_id' => ['required', Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'operation_type_id' => ['required', Rule::exists('operation_types', 'id')],
            'counterparty_agreement_id' => ['required'],
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
