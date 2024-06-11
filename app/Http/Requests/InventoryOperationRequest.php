<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryOperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'doc_number' => ['required'],
            'status_id' => ['required'],
            'active' => ['required'],
            'organization_id' => ['required'],
            'storage_id' => ['required'],
            'author_id' => ['required'],
            'date' => ['required', 'date'],
            'comment' => ['required'],
            'currency_id' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
