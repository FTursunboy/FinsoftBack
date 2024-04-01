<?php

namespace App\DTO;

use App\Http\Requests\Api\Organization\OrganizationRequest;
use App\Http\Requests\Api\Unit\UnitRequest;
use Illuminate\Http\Request;

class UnitDTO
{
    public function __construct(public string $name)
    {
    }

    public static function fromRequest(UnitRequest $request) :self
    {
        return new static(
            $request->get('name'),
        );
    }
}
