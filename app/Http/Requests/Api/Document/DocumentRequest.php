<?php

namespace App\Http\Requests\Api\Document;

use App\Models\Good;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //todo

        return [
            'date' => ['required'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required', Rule::exists('counterparty_agreements', 'id')],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'storage_id' => ['required', Rule::exists('storages', 'id')],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'saleInteger' => ['nullable', 'integer', 'required_without:salePercent'],
            'salePercent' => ['nullable', 'integer', 'required_without:saleInteger'],
            'sale_sum' => ['nullable', 'numeric'],
            'sum' => ['nullable', 'numeric'],
            'comment' => [''],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', Rule::exists('goods', 'id')],
            'goods.*.amount' => ['required', 'min:1'],
            'goods.*.price' => ['required', 'numeric'],
            'goods.*.auto_sale_percent' => ['nullable', 'numeric'],
            'goods.*.auto_sale_sum' => ['nullable', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Поле дата обязательно для заполнения.',
            'counterparty_id.required' => 'Поле контрагент обязательно для заполнения.',
            'counterparty_id.exists' => 'Выбранное значение для поле контрагент не существует.',
            'counterparty_agreement_id.required' => 'Поле договор контрагента обязательно для заполнения.',
            'counterparty_agreement_id.exists' => 'Выбранное значение для поле договор контрагента не существует.',
            'organization_id.required' => 'Поле договор контрагента обязательно для заполнения.',
            'organization_id.exists' => 'Выбранное значение для поле договор контрагента не существует.',
            'storage_id.required' => 'Поле склад обязательно для заполнения.',
            'storage_id.exists' => 'Выбранное значение для поле склад не существует.',
        ];
    }
}
