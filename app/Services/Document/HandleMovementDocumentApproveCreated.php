<?php

namespace App\Services\Document;

use App\Enums\MovementTypes;
use App\Events\SmallRemainderEvent;
use App\Models\Currency;
use App\Models\DocumentModel;
use App\Models\ExchangeRate;
use App\Models\GoodAccounting;
use App\Models\GoodDocument;


class HandleMovementDocumentApproveCreated
{
    public function __construct(public DocumentModel $document, public MovementTypes $type, public string $documentType, public int $storageId) { }

    public function handle(): void
    {
        $this->goodAccounting();
    }

    private function goodAccounting(): void
    {
        $goods = $this->document->documentGoods;

        $insertData = [];

        foreach ($goods as $good) {
            $price = GoodDocument::where('good_id', $good->good_id)->latest()->first()->price;

            $sum = $good->amount * $price;

            $insertData[] = [
                'good_id' => $good->good_id,
                'sum' => $sum ?? 0,
                'model_id' => $good->document_id,
                'model_type' => get_class($this->document),
                'created_at' => now(),
                'storage_id' => $this->storageId,
                'movement_type' => $this->type,
                'organization_id' => $this->document->organization_id,
                'amount' => $good->amount,
                'active' => true,
                'date' => $this->document->date,
                'document_type' => $this->documentType
            ];

            SmallRemainderEvent::dispatch($good->good_id);
        }

        GoodAccounting::insert($insertData);

    }

    public function getCurrency()
    {
        return Currency::where('id', $this->document->currency_id)->first()->id;
    }

    public function getDefaultCurrency()
    {
        return Currency::default()->first()->id;
    }

    public function getExcangeRate()
    {
        return ExchangeRate::query()
            ->where('currency_id', $this->getCurrency())
            ->orderBy('date', 'desc')
            ->first()
            ->value;
    }


}
