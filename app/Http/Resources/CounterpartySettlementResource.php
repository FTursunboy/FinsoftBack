<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartySettlementResource extends JsonResource
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
            'counterparty' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'counterpartyAgreement' => CounterpartyAgreementResource::make($this->whenLoaded('counterpartyAgreement')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'sale_sum' => $this->sale_sum,
            'sum' => $this->sum,
            'date' => $this->date,
            'deleted_at' => $this->deleted_at
        ];
    }
}
