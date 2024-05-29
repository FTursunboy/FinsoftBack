<?php

namespace App\Http\Requests\Api\CashRegister;

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
            'orderBy' => 'nullable|in:id,name,currency_id,organization_id,responsible_person_id' . implode(',', $fillableFields),
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
