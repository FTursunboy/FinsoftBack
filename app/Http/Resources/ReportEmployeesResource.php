<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ReportCard */
class ReportEmployeesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => EmployeeResource::make($this->employee),
            'standart_hours' => $this->standart_hours,
            'fast_hours' => $this->fact_hours
        ];
    }
}
