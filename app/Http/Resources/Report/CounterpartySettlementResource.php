<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\CounterpartyAgreementResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\GoodAccountingResource;
use App\Http\Resources\OrganizationResource;
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
            'currency' => $this->whenLoaded('currency'),
            'counterparty' => $this->counterparty,
            'income' => $this->income,
            'outcome' => $this->outcome,
            'debt' => max($this->debt, 0)
        ];
    }
}
