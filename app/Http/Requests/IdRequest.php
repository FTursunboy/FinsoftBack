<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'ids' => ['array', 'required', Rule::exists($this->getModel()->getTable(), 'id')]
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
