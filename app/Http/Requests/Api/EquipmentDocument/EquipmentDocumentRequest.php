<?php

namespace App\Http\Requests\Api\EquipmentDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'storage_id' => ['required', Rule::exists('storages', 'id')],
            'good_id' => ['required', Rule::exists('goods', 'id')],
            'comment' => ['nullable', 'string'],
            'sum' => ['required'],
            'amount' => ['required'],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', 'exists:goods,id'],
            'goods.*.amount' => ['required', 'integer'],
            'goods.*.price' => ['required'],
            'goods.*.sum' => ['required']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
