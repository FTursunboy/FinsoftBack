<?php

namespace App\Http\Requests\Api\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
            'image' => ['nullable', 'file'],
            'phone' => ['required', Rule::unique('employees','phone')],
            'email' => ['required', Rule::unique('employees','email')],
            'address' => ['required'],
            'group_id' => ['required', Rule::exists('groups', 'id')]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'phone.required' => 'Поле телефон обязательно для заполнения.',
            'phone.unique' => 'Такое значение поле телефон уже существует.',
            'email.required' => 'Поле почта обязательно для заполнения.',
            'email.unique' => 'Такое значение поле почта уже существует.',
            'address.required' => 'Поле адрес обязательно для заполнения.',
        ];
    }
}
