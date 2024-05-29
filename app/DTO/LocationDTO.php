<?php

namespace App\DTO;

use App\Http\Requests\Api\Location\LocationRequest;

class LocationDTO
{
    public function __construct(public string $name) { }

    public static function fromRequest(LocationRequest $request) :self
    {
        return new static(
            $request->get('name')
        );
    }
}
