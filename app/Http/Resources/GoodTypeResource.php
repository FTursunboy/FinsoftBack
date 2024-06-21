<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SetUpPrice */
class GoodTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,

            'good_id' => $this->good_id,
            'price_id' => $this->price_id,
        ];
    }
}
