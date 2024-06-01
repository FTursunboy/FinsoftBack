<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\OrganizationResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MovementDocument */
class MovementDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => Carbon::parse($this->date),
            'organization_id' => OrganizationResource::make($this->whenLoaded('organization')),
            'sender_storage_id' => StorageResource::make($this->whenLoaded('sender_storage')),
            'recipient_storage_id' => StorageResource::make($this->whenLoaded('recipient_storage')),
            'author_id' => UserResource::make($this->whenLoaded('author')),
            'comment' => $this->comment,
            'goods' => DocumentGoodResource::collection($this->whenLoaded('goods')),
            'goods_amount' => $this->whenLoaded('documentGoodsWithCount', function ( $query) {
                return (float) $query->first()?->total_count ?? 0;
            }),
            'deleted_at' => $this->deleted_at,
            'active' => $this->active,
        ];
    }
}
