<?php

namespace App\Http\Requests\Api\Counterparty;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use ReflectionClass;
use ReflectionMethod;

class SmsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
                'message' => 'required|string',
                'ids' => ['array', 'required', Rule::exists('counterparties', 'id')]
            ];
    }

    public function authorize(): bool
    {
        return true;
    }

    private function getFillable($model) :array
    {
        return $model->getFillable();
    }


}
