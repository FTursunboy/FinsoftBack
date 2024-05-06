<?php

namespace App\Http\Requests\Api\OrderDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderDocumentUpdateRequest extends FormRequest
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
        return [
            'date' => ['required', 'date_format:d.m.Y H:i:s'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required', Rule::exists('counterparty_agreements', 'id')],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'shipping_date' => ['nullable', 'date'],
            'order_status_id' => ['nullable', Rule::exists('order_statuses', 'id'), Rule::requiredIf(function () {
                return Str::contains(app()->request->url(), 'client/order');
            })],
            'comment' => [''],
            'summa' => ['required', 'numeric'],
            'goods' => ['nullable', 'array'],
            'goods.*.id' => ['nullable', Rule::exists('order_document_goods', 'id')],
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
        ];
    }
}
