<?php

namespace App\DTO;

use App\Http\Requests\Api\Position\PositionRequest;

class PositionDTO
{
    public function __construct(public string $name)
    {
    }

    public static function formRequest(PositionRequest $request)
    {
        return new static(
            $request->get('name')
        );
    }
}
