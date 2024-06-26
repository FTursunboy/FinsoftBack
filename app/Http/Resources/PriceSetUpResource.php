<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PriceSetUp */
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
            'organization_id' => $this->organization_id,
            'author_id' => $this->author_id,
            'goods' => SetupGoodsResource::collection($this->whenLoaded('setupGoods'))
        ];
    }
}
