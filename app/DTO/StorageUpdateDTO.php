<?php

namespace App\DTO;

use App\Http\Requests\Api\Storage\StorageRequest;

class StorageUpdateDTO
{
    public function __construct(public string $name, public int $organization_id)
    {
    }

    public static function fromRequest(StorageRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('organization_id')
        );
    }
}
