<?php

namespace App\Http\Requests\Api\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationUpdateRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'INN' => ['required', 'integer', Rule::unique('organizations', 'INN')->ignore($this->route('organization')->id)],
            'director_id' => ['required', Rule::exists('employees', 'id')],
            'chief_accountant_id' => ['required', Rule::exists('employees', 'id')],
            'address' => ['required', 'string'],
            'description' => ['']
        ];
    }
}
