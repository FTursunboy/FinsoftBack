<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CounterpartyCoordinates */
class CounterpartyCoordinatesResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'location' => CoordinateResource::make($this->location),
            'counterparty' => $this->counterparty,
        ];
    }
}
