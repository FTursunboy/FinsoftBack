<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\InventoryDocument */

class InventoryDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'responsiblePerson' => EmployeeResource::make($this->whenLoaded('responsiblePerson')),
            'author_id' => UserResource::make($this->whenLoaded('author')),
            'comment' => $this->comment,
            'inventoryGoods' => InventoryDocumentGoodResource::collection($this->whenLoaded('inventoryDocumentGoods')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
