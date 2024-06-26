<?php

namespace App\Http\Requests\Api\Plan;

use Illuminate\Foundation\Http\FormRequest;

class OldNewClientPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'year' => ['required', 'integer', 'between:1900,2100'],
            'oldNewClients' => ['required', 'array'],
            'oldNewClients.*.month_id' => ['required', 'integer', 'between:1,12'],
            'oldNewClients.*.newClient' => ['required', 'integer'],
            'oldNewClients.*.oldClient' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
