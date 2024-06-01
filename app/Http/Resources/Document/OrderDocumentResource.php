<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\CounterpartyAgreementResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\OrderStatusResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDocumentResource extends JsonResource
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
            'doc_number' => $this->doc_number,
            'date' => Carbon::parse($this->date),
            'counterparty' => CounterpartyResource::make($this->whenLoaded('counterparty')),
            'counterpartyAgreement' => CounterpartyAgreementResource::make($this->whenLoaded('counterpartyAgreement')),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'orderStatus' => OrderStatusResource::make($this->whenLoaded('orderStatus')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'currency' => CurrencyResource::make($this->whenLoaded('currency')),
            'deleted_at' => $this->deleted_at ?? null,
            'comment' => $this->comment,
            'summa' => $this->summa,
            'shippingDate' => Carbon::parse($this->shipping_date),
            'orderGoods' => OrderDocumentGoodResource::collection($this->whenLoaded('orderDocumentGoods')),
            'goods_amount' => $this->whenLoaded('documentGoodsWithCount', function ( $query) {
                return (float) $query->first()?->total_count ?? 0;
            }),
            'active' => $this->active
        ];
    }
}
