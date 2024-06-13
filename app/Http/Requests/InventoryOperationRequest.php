<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryOperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string'],
            'organization_id' => ['required'],
            'storage_id' => ['required'],
            'author_id' => ['required'],
            'date' => ['required', 'date'],
            'comment' => ['nullable', 'string'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', Rule::exists('goods', 'id')],
            'goods.*.amount' => ['required', 'min:1'],
            'goods.*.price' => [
                'required'
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
