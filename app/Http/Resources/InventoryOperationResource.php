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
            'organization' => $this->organization,
            'storage' => $this->storage,
            'author' => $this->author,
            'date' => $this->date,
            'comment' => $this->comment,
            'currency' => $this->currency,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
