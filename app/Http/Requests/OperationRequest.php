<?php

namespace App\Http\Requests;

use App\Enums\Operations;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class OperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'resource' => 'required|array',
            'resource.*.title' => 'required|string',
            'resource.*.access' => 'required|array',
            'resource.*.access.*' => ['string', Rule::enum(Operations::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
