<?php

namespace App\DTO;

use App\Http\Requests\Api\Storage\StorageEmployeeUpdateRequest;

class StorageEmployeeUpdateDTO
{
    public function __construct(public int $employee_id, public string $from, public string $to)
    {
    }

    public static function fromRequest(StorageEmployeeUpdateRequest $request) :self
    {
        return new static(
            $request->get('employee_id'),
            $request->get('from'),
            $request->get('to'),
        );
    }
}
