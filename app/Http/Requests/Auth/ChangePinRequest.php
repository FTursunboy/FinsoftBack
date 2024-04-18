<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePinRequest extends FormRequest
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
            'oldPin' => ['required'],
            'pin' => ['required', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'oldPin.required' => 'Поле старый пин-екод обязательно для заполнения.',
            'pin.integer' => 'Поле пин-код должно быть целым числом.',
            'pin.confirmed' => 'Поле пин-код не совпадает с подтверждением.',
        ];
    }
}
