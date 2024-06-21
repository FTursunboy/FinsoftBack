<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceSetUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => ['required'],
            'organization_id' => ['required', 'integer', 'exists:organizations'],
            'comment' => ['nullable', 'string'],
            'basis' => ['required', 'string'],
            'data' => ['required', 'array'],
            'data.*.good_id' => ['required', 'integer', 'exists:goods,id'],
            'data.*.price_type_id' => ['required', 'integer', 'exists:goods,id'],
            'data.*.old_price' => ['nullable', 'numeric', 'not_in:0'],
            'data.*.price' => ['nullable', 'numeric', 'not_in:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
