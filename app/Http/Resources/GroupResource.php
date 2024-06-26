<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupResource extends JsonResource
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
            'users' => UserResource::collection($this->whenLoaded('users')),
            'storages' => StorageResource::collection($this->whenLoaded('storages')),
            'employees' => EmployeeResource::collection($this->whenLoaded('employees')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
