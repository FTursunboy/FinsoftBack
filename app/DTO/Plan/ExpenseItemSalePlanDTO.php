<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\ExpenseItemSalePlanRequest;

class ExpenseItemSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $expenseItems) { }

    public static function fromRequest(ExpenseItemSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('expenseItems')
        );
    }
}
