<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
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
            'position' => PositionResource::make($this->whenLoaded('position')),
            'image' => $this->image ? Storage::url($this->image) : null,
            'group' => GroupResource::make($this->whenLoaded('group')),
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'deleted_at' => $this->deleted_at,
            'hiring' => HiringResource::make($this->whenLoaded('hiring'))

        ];
    }
}
