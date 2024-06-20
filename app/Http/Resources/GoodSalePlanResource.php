<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GoodSalePlan */
class GoodSalePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'organization_id' => $this->whenLoaded('organization'),
            'year' => $this->year,
            'goods' => GoodPlanResource::collection($this->whenLoaded('goodSalePlan')),

        ];
    }
}
