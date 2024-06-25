<?php

namespace App\Http\Requests\Api\GoodGroup;

use App\Rules\DeleteGoodGroupRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoodGroupIdsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'goodGroupIds' => ['array', 'required'],
            'priceTypeIds' => ['array', 'required'],
            'changeBySum' => [''],
            'changeByPercent' => [''],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
