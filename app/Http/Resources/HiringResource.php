<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Hiring */
class HiringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'date' => $this->data,
            'doc_number' => $this->doc_number,
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'salary' => $this->salary,
            'hiring_date' => $this->hiring_date,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'basis' => $this->basis,
            'position' => PositionResource::make($this->whenLoaded('position')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
