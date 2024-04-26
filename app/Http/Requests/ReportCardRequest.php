<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => 'required|integer|exists:organizations,id',
            'month_id' => 'required|integer|exists:months,id',
            'date' => ['required'],
            'comment' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
