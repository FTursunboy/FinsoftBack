<?php

namespace App\DTO;

use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\EmployeeMovement\EmployeeMovementRequest;
use App\Http\Requests\Api\Firing\FiringRequest;
use Illuminate\Http\Request;

class FiringDTO
{
    public function __construct(public string $date, public int $employee_id, public int $organization_id,
                                public string $firing_date, public string $basis, public ?string $comment)
    {
    }

    public static function fromRequest(FiringRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('employee_id'),
            $request->get('organization_id'),
            $request->get('firing_date'),
            $request->get('basis'),
            $request->get('comment')
        );
    }
}
