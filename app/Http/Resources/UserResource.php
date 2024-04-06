<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'login' => $this->login,
            'email'=> $this->email,
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'phone' => $this->phone,
            'status' => $this->status,
            'image' => $this->image ? Storage::url($this->image) : null,
            'group' => GroupResource::make($this->whenLoaded('group')),
            'deleted_at' => $this->deleted_at,
            'permissions' => $this->whenLoaded('permissions', function ($query) {
                return $query->pluck('name');
            }),
        ];
    }
}
