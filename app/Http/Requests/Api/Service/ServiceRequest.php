<?php

namespace App\Http\Requests\Api\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use ReflectionClass;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'counterparty_id' => ['required', Rule::exists('counterparties', 'id')],
            'counterparty_agreement_id' => ['required', Rule::exists('counterparty_agreements', 'id')],
            'organization_id' => ['required', Rule::exists('organizations', 'id')],
            'storage_id' => ['required', Rule::exists('storages', 'id')],
            'currency_id' => ['required', Rule::exists('currencies', 'id')],
            'sales_sum' => ['required', 'numeric'],
            'return_sum' => ['required', 'numeric'],
            'comment' => [''],
            'sale_goods' => ['nullable', 'array'],
            'sale_goods.*.good_id' => ['required', Rule::exists('goods', 'id')],
            'sale_goods.*.amount' => ['required', 'min:1'],
            'sale_goods.*.price' => [
                'required'
            ],
            'return_goods' => ['nullable', 'array'],
            'return_goods.*.good_id' => ['nullable', Rule::exists('goods', 'id')],
            'return_goods.*.amount' => ['nullable', 'min:1'],
            'return_goods.*.price' => [
                'nullable'
            ],
            'client_payment' => ['nullable', 'numeric', 'min:0'],
            'approve' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
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

    private function getModel()
    {
        $repository = $this->route()->getController();

        return app($repository->repository->model);
    }
}
