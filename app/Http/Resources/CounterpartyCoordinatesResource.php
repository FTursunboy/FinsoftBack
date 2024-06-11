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
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'location' => $this->location,

            'counterparty_id' => $this->counterparty_id,
        ];
    }
}
