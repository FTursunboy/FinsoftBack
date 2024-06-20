<?php

namespace App\DTO;

use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\GoodSalePlanRequest;

class GoodSalePlanDTO
{
    public function __construct(public int $year, public int $organization_id, public array $goods) { }

    public static function fromRequest(GoodSalePlanRequest $request) :self
    {
        return new static(
            $request->get('year'),
            $request->get('organization_id'),
            $request->get('goods')
        );
    }
}
