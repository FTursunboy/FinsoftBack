<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\GoodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentDocumentGoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'good' => GoodResource::make($this->whenLoaded('good')),
            'amount' => $this->amount,
            'price' => $this->price,
            'sum' => $this->sum,
            'deleted_at' => $this->deleted_at
        ];











    }
}
