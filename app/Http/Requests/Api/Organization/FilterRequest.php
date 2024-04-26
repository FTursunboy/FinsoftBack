<?php

namespace App\Http\Requests\Api\Organization;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
                'search' => 'string|nullable|max:50',
                'itemsPerPage' => 'integer|nullable',
                'orderBy' => 'nullable|in:id,name,address,description,INN,director_id,chief_accountant_id',
                'sort' => 'in:asc,desc',
                'filterData' => 'nullable|array',
            ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
