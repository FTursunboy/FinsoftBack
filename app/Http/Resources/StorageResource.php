<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StorageResource extends JsonResource
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
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'deleted_at' => $this->deleted_at

        ];
    }
}
