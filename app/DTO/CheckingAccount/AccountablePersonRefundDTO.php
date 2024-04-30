<?php

namespace App\DTO\CheckingAccount;


use App\Http\Requests\Api\CheckingAccount\AccountablePersonRefundRequest;

class AccountablePersonRefundDTO
{
    public function __construct(public string $date, public int $organization_id, public int $checking_account_id, public int $sum, public int $employee_id,
                                public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id)
    {
    }

    public static function fromRequest(AccountablePersonRefundRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('checking_account_id'),
            $request->get('sum'),
            $request->get('employee_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
            $request->get('operation_type_id'),
        );
    }
}
