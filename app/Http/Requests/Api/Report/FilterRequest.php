<?php

namespace App\Http\Requests\Api\Report;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
                'search' => 'string|nullable|max:50',
                'itemsPerPage' => 'integer|nullable',
                'orderBy' => 'nullable|in:id,good_id,begin,income,outcome,all,remainder,debt',
                'sort' => 'in:asc,desc',
                'start_date' => 'nullable',
                'end_date' => 'nullable',
                'date' => 'nullable',
                'group_id' => 'nullable|integer',
                'filterData' => 'nullable|array',
            ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
