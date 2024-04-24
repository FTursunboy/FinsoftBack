<?php

namespace App\Http\Requests\Api\Department;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'search' => 'string|nullable|max:20',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,name',
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


}
