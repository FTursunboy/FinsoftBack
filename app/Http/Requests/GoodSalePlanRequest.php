<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', 'integer', 'exists:goods,id'],
            'goods.*.month_id' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
