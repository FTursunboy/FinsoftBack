<?php

namespace App\Http\Requests\Api\Counterparty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CounterpartyRequest extends FormRequest
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
                'min:3',
                'max:25',
            ],
            'address' => [
                'string',
                'required',
                'min:3',
            ],
            'phone' => [
                'required',
                'unique:counterparties,phone',
            ],
            'email' => [
                'email',
                'required',
                'unique:counterparties,email',
            ],
            'roles' => ['required', 'array', Rule::exists('user_roles', 'id')],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'address.required' => 'Поле адрес обязательно для заполнения.',
            'phone.required' => 'Поле телефон обязательно для заполнения.',
            'phone.unique' => 'Такое значение поля телефон уже существует.',
            'email.required' => 'Поле почта обязательно для заполнения.',
            'email.unique' => 'Такое значение почта телефон уже существует.',
        ];
    }
}
