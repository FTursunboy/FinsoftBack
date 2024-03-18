<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'barcode' => ['required', 'min:3', 'unique:barcodes,barcode'],
            'good_id' => ['required', 'exists:goods,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


}
