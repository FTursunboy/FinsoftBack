<?php

namespace App\Http\Requests\Api\Good;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class CountGoodsRequest extends FormRequest
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
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'good_storage_id' => 'required|exists:storages,id',
            'good_organization_id' => 'required|exists:organizations,id',
            'document_id' => 'required|exists:documents,id',
            'good_id' => 'nullable|exists:goods,id',
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
