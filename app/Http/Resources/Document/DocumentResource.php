<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\CounterpartyAgreementResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => Carbon::parse($this->date),
            'counterparty' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'counterpartyAgreement' => CounterpartyAgreementResource::make($this->whenLoaded('counterpartyAgreement')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'deleted_at' => $this->deleted_at,
            'changes' => HistoryResource::collection($this->whenLoaded('history')),
            'comment' => $this->comment,
            'saleInteger' => $this->saleInteger,
            'salePercent' => $this->salePercent,
            'sum' => $this->whenLoaded('totalGoodsSum', function ($query) {
               return (float) $query->first()?->total_sum ?? 0;
            }),
            'goods_amount' => $this->whenLoaded('documentGoodsWithCount', function ( $query) {
                 return (float) $query->first()?->total_count ?? 0;
            }),
            'active' => $this->active,
            'goods' => DocumentGoodResource::collection($this->whenLoaded('documentGoods'))
        ];
    }
}
