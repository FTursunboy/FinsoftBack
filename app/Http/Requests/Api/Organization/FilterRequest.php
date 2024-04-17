<?php

namespace App\Http\Requests\Api\Organization;

use App\Models\Organization;
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
        $model = $this->getModel();

        $fillableFields = $this->getFillable($model);

        return [
                'search' => 'string|nullable|max:20',
                'itemsPerPage' => 'integer|nullable',
                'orderBy' => 'nullable|in:id,deleted_at,currency.name,organization.name,' . implode(',', $fillableFields),
                'sort' => 'in:asc,desc',
                'filterData' => 'nullable|array',
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

    private function getModel()
    {
        return Organization::class;
    }
}
