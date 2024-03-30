<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartyAgreementResource extends JsonResource
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
            'contract_number' => $this->contract_number,
            'date' => Carbon::parse($this->date),
            'organization_id'=> OrganizationResource::make($this->whenLoaded('organization')),
            'counterparty_id' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'contact_person' => $this->contact_person,
            'currency_id' => CurrencyResource::make($this->whenLoaded('currency')),
            'payment_id' => CurrencyResource::make($this->whenLoaded('payment')),
            'comment' => $this->comment,
            'price_type_id' => PriceTypeResource::make($this->whenLoaded('priceType')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
