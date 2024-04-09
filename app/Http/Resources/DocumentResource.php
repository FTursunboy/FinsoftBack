<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'counterparty' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'counterparty_agreement' => CounterpartyAgreementResource::make($this->whenLoaded('counterpartyAgreement')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'deleted_at' => $this->deleted_at,
            'changes' => HistoryResource::collection($this->whenLoaded('history')),
            'comment' => $this->comment,
            'saleInteger' => $this->saleInteger,
            'salePercent' => $this->salePercent,
            'goods' => DocumentGoodResource::collection($this->whenLoaded('documentGoods'))
        ];
    }
}
