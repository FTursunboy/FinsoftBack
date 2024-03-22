<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterResource extends JsonResource
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
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'responsiblePerson' => EmployeeResource::make($this->responsiblePerson),
            'balance' => 2500,
            'deleted_at' => $this->deleted_at
        ];
    }
}
