<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodHistoryResource extends JsonResource
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
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'good' => GoodResource::make($this->whenLoaded('good')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'type' => $this->type,
            'date' => $this->created_at,
        ];
    }
}
