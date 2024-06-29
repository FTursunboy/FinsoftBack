<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceSetUpResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'start_date' => $this->start_date,
            'comment' => $this->comment,
            'basis' => $this->basis,
            'active' => $this->active,
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'deleted_at' => $this->deleted_at,
            'setupGoods' => SetupGoodsResource::collection($this->whenLoaded('setupGoods'))
        ];
    }
}
