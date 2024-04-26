<?php

namespace App\DTO;

use App\Http\Requests\Api\WorkerSchedule\WorkerScheduleRequest;

class WorkerScheduleDTO
{
    public function __construct(public string $name, public int $month_id, public int $number_of_hours)
    {
    }

    public static function fromRequest(WorkerScheduleRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('month_id'),
            $request->get('number_of_hours'),
        );
    }
}
