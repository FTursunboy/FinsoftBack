<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\EmployeeMovement */
class EmployeeMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'salary' => $this->salary,
            'position_id' => PositionResource::make($this->whenLoaded('position')),
            'movement_date' => $this->movement_date,
            'schedule' => $this->schedule,
            'basis' => $this->basis,
            'deleted_at' => $this->deleted_at
        ];
    }
}
