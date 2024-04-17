<?php

namespace App\Http\Requests\Api\InventoryDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'storage_id' => ['required', Rule::exists('storages', 'id')],
            'responsible_person_id' => ['required', Rule::exists('employees', 'id')],
            'comment' => ['nullable', 'string'],
            'goods' => ['required', 'array'],
            'goods.*.id' => ['required', 'exists:inventory_document_goods,id'],
            'goods.*.good_id' => ['required', 'exists:goods,id'],
            'goods.*.accounting_quantity' => ['required', 'integer'],
            'goods.*.actual_quantity' => ['required', 'integer'],
            'goods.*.difference' => ['required', 'integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
