<?php

namespace App\DTO;

use App\Http\Requests\Api\Schedule\ScheduleRequest;

class ScheduleDTO
{
    public function __construct(public string $name, public array $data)
    {
    }

    public static function fromRequest(ScheduleRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('data'),
        );
    }
}
