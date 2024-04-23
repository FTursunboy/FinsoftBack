<?php

namespace App\DTO;

use App\Http\Requests\Api\CashRegister\CashRegisterRequest;
use App\Http\Requests\Api\Hiring\HiringRequest;
use Illuminate\Http\Request;

class HiringDTO
{
    public function __construct(public string $date, public int $employee_id, public float $salary, public string $hiring_date,
                                public int $department_id, public string $basis, public int $position_id,
                                public int $organization_id, public ?string $comment)
    {
    }

    public static function fromRequest(HiringRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('employee_id'),
            $request->get('salary'),
            $request->get('hiring_date'),
            $request->get('department_id'),
            $request->get('basis'),
            $request->get('position_id'),
            $request->get('organization_id'),
            $request->get('comment')
        );
    }
}
