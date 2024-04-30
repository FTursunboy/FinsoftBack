<?php

namespace App\DTO\CashStore;


use App\Http\Requests\Api\CashStore\AccountablePersonRefundRequest;

class AccountablePersonRefundDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id,
                                public int $sum, public int $employee_id, public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id)
    {
    }

    public static function fromRequest(AccountablePersonRefundRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('cash_register_id'),
            $request->get('sum'),
            $request->get('employee_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
            $request->get('operation_type_id'),
        );
    }
}
