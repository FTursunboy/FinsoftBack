<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'INN' => $this->INN,
            'director' => EmployeeResource::make($this->whenLoaded('director')),
            'chief_accountant' => EmployeeResource::make($this->whenLoaded('chiefAccountant')),
            'address' => $this->address,
            'description' => $this->description,
            'deleted_at' => $this->deleted_at

        ];
    }
}
