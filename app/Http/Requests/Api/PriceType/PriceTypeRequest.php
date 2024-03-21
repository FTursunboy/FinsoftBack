<?php

namespace App\Http\Requests\Api\PriceType;

use Illuminate\Foundation\Http\FormRequest;

class PriceTypeRequest extends FormRequest
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
            'currency_id' => [
                'numeric',
                'required',
                'exists:currencies,id',
            ],
            'description' => ['']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле наименование обязательно для заполнения.',
            'currency_id.required' => 'Поле валюта обязательно для заполнения.',
            'currency_id.exists' => 'Выбранное значение для валюта не существует.',
        ];
    }
}
