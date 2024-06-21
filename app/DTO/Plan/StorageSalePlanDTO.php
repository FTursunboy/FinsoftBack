<?php

namespace App\DTO\Plan;

use App\Http\Requests\Api\Plan\StorageSalePlanRequest;

class StorageSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $storages) { }

    public static function fromRequest(StorageSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('storages')
        );
    }
}
