<?php

namespace App\Http\Requests\Api\Document;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        $model = $this->getModel();

        $fillableFields = $this->getFillable($model);

        return [
            'search' => 'string|nullable|max:50',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,deleted_at,currency.name,organization.name,author.name,storage.name,counterparty.name,' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
            'currency_id' => 'nullable|numeric',
            'good_id' => 'nullable|numeric',
            'organization_id' => 'nullable|numeric',
            'storage_id' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'startDate' => 'nullable',
            'endDate' => 'nullable',
            'order_status_id' => 'nullable',
            'deleted' => 'nullable|bool',
            'active' => 'nullable|bool',
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
