<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartyCounterpartyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'counterparty' => CounterpartyResource::make($this->counterparty),
            'location' => CoordinateResource::make($this->location),
        ];
    }
}
