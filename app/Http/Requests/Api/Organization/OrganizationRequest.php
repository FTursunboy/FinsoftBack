<?php

namespace App\Http\Requests\Api\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationRequest extends FormRequest
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
            'INN' => ['required', 'integer'],
            'director_id' => ['required', Rule::exists('employees', 'id')],
            'chief_accountant_id' => ['required', Rule::exists('employees', 'id')],
            'address' => ['required', 'string'],
            'description' => ['']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'INN.required' => 'Поле ИНН обязательно для заполнения.',
            'director_id.required' => 'Поле директор обязательно для заполнения.',
            'director_id.exists' => 'Выбранное значение для директор не существует.',
            'chief_accountant_id.required' => 'Поле гл. бухгалтер обязательно для заполнения.',
            'chief_accountant_id.exists' => 'Выбранное значение для гл. бухгалтер не существует.',
            'address.required' => 'Поле адрес обязательно для заполнения.',
        ];
    }
}
