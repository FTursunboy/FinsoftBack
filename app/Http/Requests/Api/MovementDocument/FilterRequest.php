<?php

namespace App\Http\Requests\Api\MovementDocument;

use App\Models\MovementDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        $model = $this->getModel();

        $fillableFields = $this->getFillable($model);

        return [
            'search' => 'string|nullable|max:20',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,deleted_at' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
        ];
    }

    private function getFillableWithRelationships($model) :array
    {

        $fillableFields = $model->getFillable();
        $relationships = [];

        $reflector = new ReflectionClass($model);

        foreach ($reflector->getMethods() as $reflectionMethod) {
            $returnType = $reflectionMethod->getReturnType();

            if ($returnType && class_basename($returnType->getName()) == 'BelongsTo' || class_basename($returnType?->getName()) == 'hasOne') {
                $relatedModel = $model->{$reflectionMethod->getName()}()->getRelated();
                $relatedFillable = $relatedModel->getFillable();

                $relationshipName = Str::snake(Str::camel($reflectionMethod->getName()));

                foreach ($relatedFillable as $field) {
                    $relationships[] = $relationshipName . '.' . $field;
                }
            }
        }

        return array_merge($fillableFields, $relationships);

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
        return app(MovementDocument::class);
    }
}
