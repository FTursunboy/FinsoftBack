<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterpartyCoordinatesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'location' => [
                'array',
                'required',
            ],
            'location.lat' => [
                'required',
                'numeric',
                'between:-90,90',
                'not_in:0',
            ],
            'location.lon' => [
                'required',
                'numeric',
                'between:-180,180',
                'not_in:0'
            ],
            'counterparty_id' => ['required', 'exists:counterparties,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
