<?php

namespace App\DTO\CashStore;

use App\Http\Requests\Api\CashStore\OtherIncomesRequest;

class OtherIncomesDTO
{
    public function __construct(public string $date, public int $organization_id, public int $organization_bill_id,
                                public int $sum, public int $balance_article_id, public string $basis, public ?string $comment, public ?string $type)
    {
    }

    public static function fromRequest(OtherIncomesRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('organization_bill_id'),
            $request->get('sum'),
            $request->get('balance_article_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
        );
    }
}