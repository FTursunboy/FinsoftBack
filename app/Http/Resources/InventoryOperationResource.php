<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\InventoryOperation */
class InventoryOperationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'sum'  => $this->sum,
            'active' => $this->active,
            'organization_id' => $this->organization,
            'storage_id' => $this->storage,
            'author_id' => $this->author,
            'date' => $this->date,
            'comment' => $this->comment,
            'currency_id' => $this->currency,
            'status' => $this->status,
            'deleted' => $this->deleted_at,
        ];
    }
}
