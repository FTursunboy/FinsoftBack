<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\GoodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentGoodResource extends JsonResource
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
            'good' => GoodResource::make($this->whenLoaded('good')),
            'amount' => $this->amount,
            'price' => $this->price,
            'auto_sale_percent' => $this->auto_sale_percent,
            'auto_sale_sum' => $this->auto_sale_percent,
            'deleted_at' => $this->deleted_at
        ];











    }
}
