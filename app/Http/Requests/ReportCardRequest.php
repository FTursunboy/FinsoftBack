<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'doc_number' => ['required'],
            'date' => ['required'],
            'comment' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
