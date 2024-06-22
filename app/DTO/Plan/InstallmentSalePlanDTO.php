<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\InstallmentSalePlanRequest;

class InstallmentSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $goods) { }

    public static function fromRequest(InstallmentSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('goods')
        );
    }
}
