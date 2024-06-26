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
<<<<<<< HEAD
            'created_at' => $this->created_at,
=======
>>>>>>> 37bdc4ba8a15815342d66825129126448c04a8bf
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'start_date' => $this->start_date,
            'comment' => $this->comment,
            'basis' => $this->basis,
            'active' => $this->active,
<<<<<<< HEAD
            'organization' => OrganizationResource::make($this->organization),
            'author' => UserResource::make($this->author),
            'goodPrices' => SetUpResource::collection($this->whenLoaded('goodPrices')),
=======
            'organization_id' => $this->organization_id,
            'author_id' => $this->author_id,
            'goods' => SetupGoodsResource::collection($this->whenLoaded('setupGoods'))
>>>>>>> 37bdc4ba8a15815342d66825129126448c04a8bf
        ];
    }
}
