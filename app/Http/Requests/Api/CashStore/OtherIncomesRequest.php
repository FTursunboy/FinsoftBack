<?php

namespace App\Http\Requests\Api\CashStore;

use App\Enums\CashOperationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OtherIncomesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'organization_id' => ['required'],
            'cash_register_id' => ['required', Rule::exists('cash_registers', 'id')],
            'sum' => ['required'],
            'balance_article_id' => ['required', Rule::exists('balance_articles','id')],
            'operation_type_id' => ['required', Rule::exists('operation_types','id')],
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
