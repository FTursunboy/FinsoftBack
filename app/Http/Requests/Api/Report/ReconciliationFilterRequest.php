<?php

namespace App\Http\Requests\Api\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReconciliationFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
                'search' => 'string|nullable|max:50',
                'itemsPerPage' => 'integer|nullable',
                'orderBy' => 'nullable|in:id,good_id,begin,income,outcome,all,remainder',
                'sort' => 'in:asc,desc',
                'from' => 'nullable',
                'to' => 'nullable',
                'date' => 'nullable',
                'filterData' => 'nullable|array',
            ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
