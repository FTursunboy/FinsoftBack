<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CashStore */
class CashStoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization_id' => $this->organization_id,
            'cashRegister_id' => $this->cashRegister_id,
            'sum' => $this->sum,
            'counterparty_id' => $this->counterparty_id,
            'counterparty_agreement_id' => $this->counterparty_agreement_id,
            'basis' => $this->basis,
            'comment' => $this->comment,
            'author_id' => $this->author_id,
            'organizationBill_id' => $this->organizationBill_id,
            'senderCashRegister_id' => $this->senderCashRegister_id,
            'employee_id' => $this->employee_id,
            'balanceKey_id' => $this->balanceKey_id,
        ];
    }
}
