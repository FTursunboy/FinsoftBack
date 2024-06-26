<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceSetUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => ['required'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'comment' => ['nullable', 'string'],
            'basis' => ['required', 'string'],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', 'integer', 'exists:goods,id'],
            'goods.prices' => ['array'],
            'goods.*.prices.*.price_type_id' => ['required', 'integer', 'exists:price_types,id'],
            'goods.*.prices.*.old_price' => ['nullable', 'numeric', 'not_in:0'],
            'goods.*.prices.*.new_price' => ['nullable', 'numeric', 'not_in:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
