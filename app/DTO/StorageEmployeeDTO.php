<?php

namespace App\DTO;

use App\Http\Requests\Api\Storage\StorageEmployeeRequest;

class StorageEmployeeDTO
{
    public function __construct(public int $employee_id, public string $from, public string $to)
    {
    }

    public static function fromRequest(StorageEmployeeRequest $request) :self
    {
        return new static(
            $request->get('employee_id'),
            $request->get('from'),
            $request->get('to'),
        );
    }
}
