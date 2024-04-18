<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDocumentGoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'good' => GoodResource::make($this->goods),
            'amount' => $this->amount,
            'price' => $this->price,
            'autoSalePercent' => $this->auto_sale_percent,
            'autiSaleSum' => $this->auto_sale_sum
        ];
    }
}
