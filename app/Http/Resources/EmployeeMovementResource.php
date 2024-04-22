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
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'employee_id' => $this->employee_id,
            'salary' => $this->salary,
            'position' => $this->position,
            'movement_date' => $this->movement_date,
            'schedule' => $this->schedule,
            'basis' => $this->basis,
        ];
    }
}
