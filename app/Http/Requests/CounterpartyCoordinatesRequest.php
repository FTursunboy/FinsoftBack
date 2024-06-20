<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterpartyCoordinatesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locations' => [
                'array',
                'required',
            ],
            'locations.*.lat' => [
                'required',
                'numeric',
                'between:-90,90',
                'not_in:0',
            ],
            'locations.*.lon' => [
                'required',
                'numeric',
                'between:-180,180',
                'not_in:0',
            ],
            'locations.*.date' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ]

        ];

    }

    public function authorize(): bool
    {
        return true;
    }
}
