<?php

namespace App\Services;

use App\Enums\MovementTypes;
use App\Events\SmallRemainderEvent;
use App\Models\Balance;
use App\Models\BalanceArticle;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\Document;
use App\Models\DocumentModel;
use App\Models\ExchangeRate;
use App\Models\Good;
use App\Models\GoodAccounting;


class HandleMovementDocumentApproveCreated
{
    public function __construct(public DocumentModel $document, public MovementTypes $type, public string $documentType) { }

    public function handle(): void
    {
        $this->goodAccounting();
    }

    private function goodAccounting(): void
    {
        $goods = $this->document->documentGoods;

        $insertData = [];

        foreach ($goods as $good) {
            $sum = $good->amount * $good->price;

            if ($this->document->currency_id !== $this->getDefaultCurrency()) {
                $sum = $good->amount * $good->price * $this->getExcangeRate();
            }

            $insertData[] = [
                'good_id' => $good->good_id,
                'sum' => $sum ?? 0,
                'model_id' => $good->document_id,
                'model_type' => get_class($this->document),
                'created_at' => now(),
                'storage_id' => $this->document->storage_id,
                'movement_type' => $this->type,
                'organization_id' => $this->document->organization_id,
                'amount' => $good->amount,
                'active' => true,
                'date' => $this->document->date,
                'document_type' => $this->documentType
            ];

            if ($this->type->value == MovementTypes::Income->value) {
                Good::where('id', $good->good_id)->increment('amount', $good->amount);
            } else {
                Good::where('id',$good->good_id)->decrement('amount', $good->amount);
            }

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