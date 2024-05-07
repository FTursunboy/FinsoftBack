<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SalaryDocumentEmployees */
class SalaryDocumentEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'oklad' => $this->oklad,
            'worked_hours' => $this->worked_hours,
            'salary' => $this->salary,
            'employee' => EmployeeResource::make($this->employee),
            'author' => $this->whenLoaded('author'),
            'comment' => $this->comment
        ];
    }
}
