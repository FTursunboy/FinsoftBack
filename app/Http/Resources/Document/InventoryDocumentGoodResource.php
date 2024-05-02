<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\GoodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryDocumentGoodResource extends JsonResource
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
            'accounting_quantity' => $this->accounting_quantity,
            'actual_quantity' => $this->actual_quantity,
            'difference' => $this->difference,
        ];
    }
}
