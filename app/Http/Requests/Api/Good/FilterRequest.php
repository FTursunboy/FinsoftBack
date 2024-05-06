<?php

namespace App\Http\Requests\Api\Good;

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
            'search' => 'string|nullable|max:50',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,deleted_at,currency.name,storage.name,' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
            'good_group_id' => 'nullable|numeric',
            'name' => 'nullable|string',
            'vendor_code' => 'nullable',
            'category_id' => 'nullable|integer',
            'unit_id' => 'nullable|integer',
            'storage_id' => 'nullable|integer',
            'good_storage_id' => 'nullable|exists:storages,id',
            'good_organization_id' => 'nullable|exists:organizations,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    private function getFillable($model): array
    {
        return $model->getFillable();
    }

    private function getModel()
    {
        $repository = $this->route()->getController();

        return app($repository->repository->model);
    }
}
