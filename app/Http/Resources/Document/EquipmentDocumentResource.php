<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\CounterpartyAgreementResource;
use App\Http\Resources\CounterpartyResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\GoodResource;
use App\Http\Resources\HistoryResource;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => Carbon::parse($this->date),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
            'storage' => StorageResource::make($this->whenLoaded('storage')),
            'author' => UserResource::make($this->whenLoaded('author')),
            'good' => GoodResource::make($this->whenLoaded('good')),
            'deleted_at' => $this->deleted_at,
            'comment' => $this->comment,
            'active' => $this->active,
            'goods' => EquipmentDocumentGoodResource::collection($this->whenLoaded('documentGoods'))
        ];
    }
}
