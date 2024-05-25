<?php

namespace App\Http\Requests\Api\OrganizationBill;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationBillRequest extends FormRequest
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
            'name' => [
                'string',
                'required',
            ],
            'organization_id' => [
                'exists:organizations,id',
                'required',
                'numeric',
            ],
            'currency_id' => [
                'exists:currencies,id',
                'required',
                'numeric',
            ],
            'bill_number' => [
                'string',
                'required',
            ],
            'date' => [
                'required',
                'date'
            ],
            'comment' => ['']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'bill_number.required' => 'Поле номер счета обязательно для заполнения.',
            'date.required' => 'Поле дата обязательно для заполнения.',
            'currency_id.required' => 'Поле валюта обязательно для заполнения.',
            'currency_id.exists' => 'Выбранное значение для поле валюта не существует.',
            'organization_id.required' => 'Поле организация обязательно для заполнения.',
            'organization_id.exists' => 'Выбранное значение для поле организация не существует.',
        ];
    }

}
