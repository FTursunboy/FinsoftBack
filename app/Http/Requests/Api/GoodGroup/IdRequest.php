<?php

namespace App\Http\Requests\Api\GoodGroup;

use App\Rules\DeleteGoodGroupRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => ['array', 'required', Rule::exists($this->getModel()->getTable(), 'id'), new DeleteGoodGroupRule]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


    private function getModel()
    {
        $repository = $this->route()->getController();

        return app($repository->repository->model);
    }
}
