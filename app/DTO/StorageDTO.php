<?php

namespace App\DTO;

use App\Http\Requests\Api\Storage\StorageRequest;

class StorageDTO
{
    public function __construct(public string $name, public int $organization_id, public ?array $storage_data, public ?int $group_id)
    {
    }

    public static function fromRequest(StorageRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('organization_id'),
            $request->get('storage_data'),
            $request->get('group_id'),
        );
    }
}
