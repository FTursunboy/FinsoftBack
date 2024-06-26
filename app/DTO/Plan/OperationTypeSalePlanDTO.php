<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\OperationTypeSalePlanRequest;

class OperationTypeSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $operationTypes) { }

    public static function fromRequest(OperationTypeSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('operationTypes')
        );
    }
}
