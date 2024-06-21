<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\GoodResource;
use App\Http\Resources\MonthResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\GoodPlan */
class GoodPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'quantity' => $this->quantity,
            'month' =>  MonthResource::make($this->whenLoaded('month')),
            'good' => GoodResource::make($this->whenLoaded('good')),
        ];
    }
}
