<?php

namespace App\Http\Requests\Api\User;

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
            'orderBy' => 'nullable|in:id,deleted_at,currency.name,organization.name,' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
            'deleted' => 'nullable|bool',
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
