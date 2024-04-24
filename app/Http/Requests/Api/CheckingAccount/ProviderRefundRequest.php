<?php

namespace App\Http\Requests\Api\CheckingAccount;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderRefundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required'],
            'checking_account_id' => ['required', Rule::exists('organization_bills', 'id')],
            'sum' => ['required'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required', Rule::exists('counterparty_agreements', 'id')],
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
