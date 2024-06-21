<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SetUpPrice */
class SetUpResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'good' => GoodResource::make($this->whenLoaded('good')),
            'priceType' => PriceTypeResource::make($this->whenLoaded('priceType')),
            'oldPrice' => $this->old_price,
            'newPrice' => $this->new_price
        ];
    }
}
