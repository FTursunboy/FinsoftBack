<?php

namespace App\Http\Requests\Api\CounterpartyAgreement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CounterpartyAgreementUpdateRequest extends FormRequest
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
            'name' => ['string', 'required'],
            'date' => ['required'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'contact_person' => ['required'],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'payment_id' => ['required', Rule::exists('currencies', 'id')],
            'comment' => [''],
            'price_type_id' => ['required', Rule::exists('price_types', 'id')],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'date.required' => 'Поле дата обязательно для заполнения.',
            'contact_person.required' => 'Поле контактное лицо обязательно для заполнения.',
            'currency_id.required' => 'Поле валюта обязательно для заполнения.',
            'currency_id.exists' => 'Выбранное значение для поле валюта не существует.',
            'organization_id.required' => 'Поле организация обязательно для заполнения.',
            'organization_id.exists' => 'Выбранное значение для поле организация не существует.',
            'counterparty_id.required' => 'Поле контрагент обязательно для заполнения.',
            'counterparty_id.exists' => 'Выбранное значение для поле контрагент не существует.',
            'price_type_id.required' => 'Поле вид цены обязательно для заполнения.',
            'price_type_id.exists' => 'Выбранное значение для поле вид цены не существует.',

        ];
    }
}
