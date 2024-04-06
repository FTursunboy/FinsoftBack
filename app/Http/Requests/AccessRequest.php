<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessRequest extends FormRequest
{
    public function rules()
    {
        return [
            'access' => 'nullable|boolean',
            'next_payment' => 'string|nullable'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
