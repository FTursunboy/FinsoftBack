<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\MonthResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GoodPlan */
class EmployeePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'sum' => $this->sum,
            'month' =>  MonthResource::make($this->whenLoaded('month')),
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
        ];
    }
}
