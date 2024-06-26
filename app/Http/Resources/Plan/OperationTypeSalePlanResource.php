<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationTypeSalePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'organization_id' => $this->whenLoaded('organization'),
            'year' => $this->year,
            'operationTypes' => OperationTypePlanResource::collection($this->whenLoaded('operationTypeSalePlan')),
        ];
    }
}
