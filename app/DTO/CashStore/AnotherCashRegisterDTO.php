<?php

namespace App\DTO\CashStore;


use App\Http\Requests\Api\CashStore\AnotherCashRegisterRequest;

class AnotherCashRegisterDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id,
                                public int $sum, public int $sender_cash_register_id, public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id)
    {
    }

    public static function fromRequest(AnotherCashRegisterRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('cash_register_id'),
            $request->get('sum'),
            $request->get('sender_cash_register_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
            $request->get('operation_type_id'),
        );
    }
}
