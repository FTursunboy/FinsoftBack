<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'doc_number' => ['required'],
            'date' => ['required'],
            'organization_id' => ['required'],
            'cashRegister_id' => ['required'],
            'sum' => ['required'],
            'counterparty_id' => ['required'],
            'counterparty_agreement_id' => ['required'],
            'basis' => ['required'],
            'comment' => ['nullable'],
            'author_id' => ['nullable'],
            'organizationBill_id' => ['nullable', 'integer'],
            'senderCashRegister_id' => ['nullable', 'integer'],
            'employee_id' => ['nullable', 'integer'],
            'balanceKey_id' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
