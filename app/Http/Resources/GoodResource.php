<?php

namespace App\Http\Resources;

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
            'category_id' => CategoryResource::make($this->whenLoaded('category')),
            'unit_id' => $this->unit_id,
            'barcode' => $this->barcode,
            'storage_id' => StorageResource::make($this->whenLoaded('storage')),
            'deleted_at' => $this->deleted_at

        ];
    }
}
