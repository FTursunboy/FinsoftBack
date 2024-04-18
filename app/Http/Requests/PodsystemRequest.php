<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PodsystemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'permissions' => 'required|array',
            'permissions.*.name' => 'required|string|exists:permissions,name',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
