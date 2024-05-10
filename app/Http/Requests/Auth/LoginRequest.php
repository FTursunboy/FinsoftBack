<?php

namespace App\Http\Requests\Auth;

use App\Enums\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            'login' => ['required', 'exists:users,login'],
            'password' => ['required'],
            'pin' => ['nullable', 'string'],
            'device' => ['nullable', Rule::enum(Device::class)]
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Поле логин обязательно для заполнения.',
            'password.required' => 'Поле пароль обязательно для заполнения.',
        ];
    }
}
