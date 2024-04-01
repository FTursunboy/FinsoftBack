<?php

namespace App\Http\Requests\Api\User;

use App\Enums\Groups;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends FormRequest
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
            'organization_id' => ['exists:organizations,id'],
            'login' => ['required', 'unique:users,login'],
            'password' => ['required'],
            'phone' => ['unique:users,phone'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'image' => ['nullable', 'file'],
            'group_id' => ['integer', 'required', 'exists:groups,id']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'login.required' => 'Поле логин обязательно для заполнения.',
            'login.unique' => 'Такое значение поля логин уже существует.',
            'phone.unique' => 'Такое значение поля телефон уже существует.',
            'password.required' => 'Поле пароль обязательно для заполнения.',
            'organization_id.required' => 'Поле организация обязательно для заполнения.',
            'organization_id.exists' => 'Выбранное значение для организация не существует.',
            'email' => 'Поле почта имеет ошибочный формат.',
            'email.unique' => 'Такое значение поля почта уже существует.',
        ];
    }
}
