<?php

namespace App\Http\Requests\Api\Currency;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'digital_code' => [
                'numeric',
                'required',
                'digits_between:2,3',
            ],
            'symbol_code' => [
                'string',
                'required',
                'max:3',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'digital_code.required' => 'Поле цифровой код обязательно для заполнения.',
            'symbol_code.required' => 'Поле символьный код обязательно для заполнения.',

        ];
    }
}
