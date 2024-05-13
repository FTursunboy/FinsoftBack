<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'creditArticle' => BalanceArticleResource::make($this->whenLoaded('creditArticle')),
            'debitArticle' => BalanceArticleResource::make($this->whenLoaded('debitArticle')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'sum' => $this->sum,
            'date' => $this->date,
            'deleted_at' => $this->deleted_at
        ];
    }
}
