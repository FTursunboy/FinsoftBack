<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Firing */
class FiringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'firing_date' => $this->firing_date,
            'basis' => $this->basis,
            'author' => UserResource::make($this->whenLoaded('author')),
            'comment' => $this->comment,
        ];
    }
}
