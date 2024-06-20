<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\GoodSalePlanRequest;

class GoodSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $goods) { }

    public static function fromRequest(GoodSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('goods')
        );
    }
}
