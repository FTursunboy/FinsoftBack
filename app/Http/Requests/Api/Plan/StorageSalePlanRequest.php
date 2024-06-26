<?php

namespace App\Http\Requests\Api\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StorageSalePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'storages' => ['required', 'array'],
            'storages.*.storage_id' => ['required', 'integer', 'exists:storages,id'],
            'storages.*.month_id' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
