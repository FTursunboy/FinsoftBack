<?php

namespace App\DTO\CashStore;

use App\Http\Requests\Api\CashStore\SalaryPaymentRequest;

class SalaryPaymentDTO
{
    public function __construct(public string $date, public int $organization_id, public int $cash_register_id, public float $sum, public int $employee_id,
                                public int $month_id, public string $basis, public ?string $comment, public ?string $type, public int $operation_type_id)
    {
    }

    public static function fromRequest(SalaryPaymentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('cash_register_id'),
            $request->get('sum'),
            $request->get('employee_id'),
            $request->get('month_id'),
            $request->get('basis'),
            $request->get('comment'),
            $request->get('type'),
            $request->get('operation_type_id'),
        );
    }
}
