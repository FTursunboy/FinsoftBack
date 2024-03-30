<?php

namespace App\Http\Requests\Api\Image;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImageRequest extends FormRequest
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
            'good_id' => ['required', 'exists:goods,id'],
            'image' => ['required', 'file'],
            'is_main' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'good_id.required' => 'Поле продукт обязательно для заполнения.',
            'good_id.exists' => 'Выбранное значение для продукт не существует.',
        ];
    }
}
