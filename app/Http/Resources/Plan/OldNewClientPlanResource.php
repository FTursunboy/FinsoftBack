<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\MonthResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OldNewClientPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'new_client' => $this->new_client,
            'old_client' => $this->old_client,
            'month' =>  MonthResource::make($this->whenLoaded('month')),
        ];
    }
}
