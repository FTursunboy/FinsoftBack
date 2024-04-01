<?php

namespace App\Http\Requests\Api\CashRegister;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterUpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'organization_id' => ['required', 'exists:organizations,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'currency_id.required' => 'Поле валюта обязательно для заполнения.',
            'currency_id.exists' => 'Выбранное значение для поле валюта не существует.',
            'organization_id.required' => 'Поле организация обязательно для заполнения.',
            'organization_id.exists' => 'Выбранное значение для поле организация не существует.',
        ];
    }
}
