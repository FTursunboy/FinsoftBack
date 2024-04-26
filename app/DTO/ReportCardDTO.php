<?php

namespace App\DTO;

use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\ReportCardRequest;
use Illuminate\Http\Request;

class ReportCardDTO
{
    public function __construct(public string $date, public int $organization_id, public int $month_id, public string $comment)
    {
    }

    public static function fromRequest(ReportCardRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('month_id'),
            $request->get('comment')
        );
    }
}
