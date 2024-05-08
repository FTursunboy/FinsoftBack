<?php

namespace App\Http\Resources;

use App\Models\GoodAccounting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodResource extends JsonResource
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
            'name' => $this->name,
            'vendor_code' => $this->vendor_code,
            'description' => $this->description,
            'unit_id' => UnitResource::make($this->whenLoaded('unit')),
            'storage_id' => StorageResource::make($this->whenLoaded('storage')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'amount' => (int) $this->amount,
            'goodGroup' => GoodGroupResource::make($this->whenLoaded('goodGroup')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
