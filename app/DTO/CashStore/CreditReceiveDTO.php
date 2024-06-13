<?php

namespace App\DTO\CashStore;


use App\Http\Requests\Api\CashStore\CreditReceiveRequest;

class CreditReceiveDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id, public float $sum, public int $counterparty_id, public int $counterparty_agreement_id,
                                public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id, public ?string $sender, public ?string $recipient)
    {
    }

    public static function fromRequest(CreditReceiveRequest $request) :self
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
            $request->get('operation_type_id'),
            $request->get('sender'),
            $request->get('recipient'),
        );
    }
}
