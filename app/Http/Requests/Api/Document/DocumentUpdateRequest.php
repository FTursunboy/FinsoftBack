<?php

namespace App\Http\Requests\Api\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DocumentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required', Rule::exists('counterparty_agreements', 'id')],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'storage_id' => ['required', Rule::exists('storages', 'id')],
            'goods' => ['nullable', 'array'],
            'goods.*.id' => ['nullable', Rule::exists('good_documents', 'id')],
            'goods.*.good_id' => ['required', Rule::exists('goods', 'id')],
            'goods.*.amount' => ['required', 'min:1'],
            'goods.*.price' => ['required', 'numeric'],
            'goods.*.created' => ['nullable', 'bool'],
            'goods.*.updated' => ['nullable', 'bool'],
            'goods.*.deleted' => ['nullable', 'bool']
        ];
    }
}
