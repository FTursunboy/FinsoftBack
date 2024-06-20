<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer'],
            'year' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
