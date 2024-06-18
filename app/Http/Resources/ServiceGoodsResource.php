<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ServiceGoods */
class ServiceGoodsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'good_id' => $this->good_id,
            'service_id' => $this->service_id,
            'type' => $this->type,
            'price' => $this->price,
            'amount' => $this->amount,
        ];
    }
}
