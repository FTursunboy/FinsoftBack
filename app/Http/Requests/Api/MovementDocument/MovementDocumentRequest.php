<?php

namespace App\Http\Requests\Api\MovementDocument;

use Illuminate\Foundation\Http\FormRequest;

class MovementDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:d.m.Y H:i:s'],
            'organization_id' => ['required', 'integer'],
            'sender_storage_id' => ['required', 'integer'],
            'recipient_storage_id' => ['required', 'integer'],
            'comment' => ['nullable', 'string'],
            'goods' => ['required', 'array'],
            'goods.*.good_id' => ['required', 'exists:goods,id'],
            'goods.*.amount' => ['required', 'integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
