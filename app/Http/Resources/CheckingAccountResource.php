<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CheckingAccount */
class CheckingAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'id' => $this->id,
            'date' => $this->date,
            'doc_number' => $this->doc_number,
            'counterparty' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'counterpartyAgreement' => CounterpartyAgreementResource::make($this->whenLoaded('counterpartyAgreement')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'checkingAccount' => OrganizationBillResource::make($this->whenLoaded('checkingAccount')),
            'senderCashRegister' => CashRegisterResource::make($this->whenLoaded('senderCashRegister')),
            'sender' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'organizationBill' => OrganizationBillResource::make($this->whenLoaded('organizationBill')),
            'operationType' => OperationTypeResource::make($this->whenLoaded('operationType')),
            'sum' => $this->sum,
            'basis' => $this->basis,
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'active' => $this->active
        ];
    }
}
