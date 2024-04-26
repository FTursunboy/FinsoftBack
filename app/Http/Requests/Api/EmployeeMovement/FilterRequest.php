<?php

namespace App\Http\Requests\Api\EmployeeMovement;

use App\Models\EmployeeMovement;
use App\Models\Hiring;
use App\Models\MovementDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use ReflectionClass;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        $model = $this->getModel();

        $fillableFields = $this->getFillableWithRelationships($model);

        return [
            'search' => 'string|nullable|max:50',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,deleted_at,' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
        ];
    }

    private function getFillableWithRelationships($model): array
    {
        $fillableFields = $this->getFillable($model);
        $relationships = ['employee', 'organization', 'department', 'position'];

        $relationshipFields = [];
        foreach ($relationships as $relation) {
            $relatedModel = $model->{$relation}()->getRelated();

            $relatedFillable = $relatedModel->getFillable();

            foreach ($relatedFillable as $field) {
                $relationshipFields[] = $relation . '.' . $field;
            }
        }

        return array_merge($fillableFields, $relationshipFields);
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
        return app(EmployeeMovement::class);
    }
}
