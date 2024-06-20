<?php

namespace App\Services\Document\Equipment;

use App\Enums\MovementTypes;
use App\Events\SmallRemainderEvent;
use App\Models\Balance;
use App\Models\BalanceArticle;
use App\Models\CounterpartySettlement;
use App\Models\Currency;
use App\Models\DocumentModel;
use App\Models\ExchangeRate;
use App\Models\Good;
use App\Models\GoodAccounting;
use Illuminate\Support\Facades\DB;


class GoodAccountingService
{
    public function __construct(public DocumentModel $document, public string $documentType) { }

    public function handle(): void
    {
        $this->goodAccounting();
    }

    public function goodAccounting(): void
    {
        try {
            DB::transaction(function () {

               GoodAccounting::create([
                    'good_id' => $this->document->good_id,
                    'sum' => $this->document->sum,
                    'model_id' => $this->document->id,
                    'model_type' => get_class($this->document),
                    'storage_id' => $this->document->storage_id,
                    'created_at' => now(),
                    'movement_type' => MovementTypes::Income->value,
                    'organization_id' => $this->document->organization_id,
                    'amount' => $this->document->amount,
                    'active' => true,
                    'date' => $this->document->date,
                    'document_type' => $this->documentType
                ]);

                Good::where('id',$this->document->good_id)->increment('amount', $this->document->amount);

                $goods = $this->document->documentGoods;

                $insertData = [];

                foreach ($goods as $good) {

                    $insertData[] = [
                        'good_id' => $good->good_id,
                        'sum' => $good->sum,
                        'model_type' => get_class($this->document),
                        'model_id' => $this->document->id,
                        'storage_id' => $this->document->storage_id,
                        'created_at' => now(),
                        'movement_type' => MovementTypes::Outcome->value,
                        'organization_id' => $this->document->organization_id,
                        'amount' => $good->amount,
                        'active' => true,
                        'date' => $this->document->date,
                        'document_type' => $this->documentType
                    ];

                    Good::where('id',$good->good_id)->decrement('amount', $good->amount);

                    SmallRemainderEvent::dispatch($good->good_id);
                }

                GoodAccounting::insert($insertData);
            });
        } catch (\Exception $exception) {
            dd($exception);
        }


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
