<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\CounterpartyAgreementResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\GoodAccountingResource;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'debt_at_begin' => $this->debt_at_begin,
            'debt_at_end' => $this->debt_at_end
        ];
    }
}
