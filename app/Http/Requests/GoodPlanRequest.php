<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'good_sale_plan_id' => ['required', 'exists:good_sale_plans'],
            'month_id' => ['required', 'exists:months'],
            'good_id' => ['required', 'exists:goods'],
            'quantity' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
