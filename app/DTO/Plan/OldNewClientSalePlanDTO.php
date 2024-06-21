<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\OldNewClientPlanRequest;

class OldNewClientSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $oldNewClients) { }

    public static function fromRequest(OldNewClientPlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('oldNewClients')
        );
    }
}
