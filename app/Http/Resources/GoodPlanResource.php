<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GoodPlan */
class GoodPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'quantity' => $this->quantity,

            'good_sale_plan_id' => $this->good_sale_plan_id,
            'month_id' => $this->month_id,
            'good_id' => $this->good_id,

            'goodSalePlan' => new GoodSalePlanResource($this->whenLoaded('goodSalePlan')),
        ];
    }
}
