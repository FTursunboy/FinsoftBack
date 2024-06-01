<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
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
            'user' => UserResource::make($this->whenLoaded('user')),
            'status' => $this->status,
            'date' => $this->created_at,
            'ip_address' => "172.1.1.1",
            "pc_name" => "User",
            'changes' => ChangeResource::collection($this->whenLoaded('changes'))
        ];
    }
}
