<?php

namespace App\Traits;

use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CalculateSum
{
    private function calculateSum(Document $document, bool $isCreate = false)
    {
        $goods = $document->documentGoods;
        $sum = 0;
        $saleSum = 0;

        foreach ($goods as $good) {
            $basePrice = $good->price * $good->amount;
            $sum += $basePrice;

            $discountAmount = 0;
            if (isset($good->auto_sale_percent)) {
                $discountAmount += $basePrice * ($good->auto_sale_percent / 100);
            }
            if (isset($good->auto_sale_sum)) {
                $discountAmount += $good->auto_sale_sum;
            }

            $priceAfterGoodDiscount = $basePrice - $discountAmount;
            $saleSum += $priceAfterGoodDiscount;
        }

        $documentDiscount = 0;
        if (isset($document->salePercent)) {
            $documentDiscount += $saleSum * ($document->salePercent / 100);
        }
        if (isset($document->saleInteger)) {
            $documentDiscount += $document->saleInteger;
        }

        $saleSum -= $documentDiscount;

        $document->sum = $sum;
        $document->sale_sum = $saleSum;

        if ($isCreate) {
            $document->saveQuietly();

            return true;
        }
        $document->save();

    }


}
