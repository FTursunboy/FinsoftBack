<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\EmployeeSalePlanRequest;

class EmployeeSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $employees) { }

    public static function fromRequest(EmployeeSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('employees')
        );
    }
}
