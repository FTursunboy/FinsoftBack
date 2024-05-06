<?php

namespace App\Http\Requests\Api\Exchange;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeUpdateRequest extends FormRequest
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
            'date' => [
                'date_format:d.m.Y H:i:s',
                'required'
            ],
            'value' => [
                'numeric',
                'required',
                'between:0.0001,9999999.9999',
            ],
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Поле дата обязательно для заполнения.',
            'value.required' => 'Поле значение обязательно для заполнения.',
        ];
    }

}
