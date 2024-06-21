<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'good_id' => ['required', 'exists:goods'],
            'price_id' => ['required', 'exists:prices'],
            'old_price' => ['nullable', 'numeric'],
            'new_price' => ['nullable', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
