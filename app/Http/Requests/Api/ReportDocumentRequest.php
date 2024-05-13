<?php

namespace App\Http\Requests\Api;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class ReportDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => 'string|nullable|max:20',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable',
            'sort' => 'in:asc,desc',
            'filterData' => 'array|nullable'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
