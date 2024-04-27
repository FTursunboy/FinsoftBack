<?php

namespace App\Http\Requests\Api\ReportCard;

use Illuminate\Foundation\Http\FormRequest;

class FilterEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => 'required|integer|exists:organizations,id',
            'month_id' => 'required|integer|exists:months,id',
            'itemsPerPage' => 'nullable|integer',
            'search' => 'string|nullable|max:20',
            'orderBy' => 'nullable|in:id,deleted_at,employee.name,',
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
