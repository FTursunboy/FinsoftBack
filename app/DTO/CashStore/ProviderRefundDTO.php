<?php

namespace App\DTO\CashStore;


use App\Http\Requests\Api\CashStore\ProviderRefundRequest;

class ProviderRefundDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id,
                                public int $sum, public int $counterparty_id, public int $counterparty_agreement_id, public string $basis, public ?string $comment, public ?string $type)
    {
    }

    public static function fromRequest(ProviderRefundRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('cash_register_id'),
            $request->get('sum'),
            $request->get('counterparty_id'),
            $request->get('counterparty_agreement_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
        );
    }
}
