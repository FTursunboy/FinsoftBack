<?php

namespace App\DTO;

use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\EmployeeMovement\EmployeeMovementRequest;
use Illuminate\Http\Request;

class EmployeeMovementDTO
{
    public function __construct(public string $date, public int $employee_id, public float  $salary,
                                public int $position_id, public string $movement_date, public string $basis,
                                public int $department_id, public int $organization_id, public ?string $comment, public int $schedule_id)
    {
    }

    public static function fromRequest(EmployeeMovementRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('employee_id'),
            $request->get('salary'),
            $request->get('position_id'),
            $request->get('movement_date'),
            $request->get('basis'),
            $request->get('department_id'),
            $request->get('organization_id'),
            $request->get('comment'),
            $request->get('schedule_id')
        );
    }
}
