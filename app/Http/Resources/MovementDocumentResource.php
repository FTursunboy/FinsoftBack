<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MovementDocument */
class MovementDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization_id' => OrganizationResource::make($this->whenLoaded('organization')),
            'sender_storage_id' => StorageResource::make($this->whenLoaded('senderStorage')),
            'recipient_storage_id' => StorageResource::make($this->whenLoaded('recipientStorage')),
            'author_id' => UserResource::make($this->whenLoaded('author')),
            'comment' => $this->comment,
        ];
    }
}
