<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodAccountingResource extends JsonResource
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
            'movement_type' => $this->movement_type,
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'good' => GoodResource::make($this->whenLoaded('good')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'amount' => $this->amount,
            'sum' => $this->sum,
            'date' => $this->date,
            'deleted_at' => $this->deleted_at
        ];
    }
}
