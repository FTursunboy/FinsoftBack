<?php

namespace App\DTO\CashStore;

use App\Http\Requests\Api\CashStore\WithdrawalRequest;

class WithdrawalDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id, public float $sum, public int $organization_bill_id,
                                public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id, public ?string $sender, public ?string $recipient)
    {
    }

    public static function fromRequest(WithdrawalRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('cash_register_id'),
            $request->get('sum'),
            $request->get('organization_bill_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
            $request->get('operation_type_id'),
            $request->get('sender'),
            $request->get('recipient'),
        );
    }
}
