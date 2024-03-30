<?php

namespace App\Http\Requests\Api\CashRegister;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CashRegisterRequest extends FormRequest
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
            'responsible_person_id' => ['required', 'exists:employees,id'],
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
            'responsible_person_id.required' => 'Поле ответственное лицо обязательно для заполнения.',
            'responsible_person_id.exists' => 'Выбранное значение для поле ответственное лицо не существует.',
        ];
    }
}
