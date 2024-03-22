<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationBillResource extends JsonResource
{
    /**
     * Transform the resource into afdsn array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => Carbon::parse($this->date),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'bill_number' => $this->bill_number,
            'comment' => $this->comment,
            'balance' => 2500,
            'deleted_at' => $this->deleted_at
        ];
    }
}
