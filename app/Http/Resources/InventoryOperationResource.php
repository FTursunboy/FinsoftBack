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
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'status_id' => $this->status_id,
            'active' => $this->active,
            'organization_id' => $this->organization_id,
            'storage_id' => $this->storage_id,
            'author_id' => $this->author_id,
            'date' => $this->date,
            'comment' => $this->comment,
            'currency_id' => $this->currency_id,
        ];
    }
}
