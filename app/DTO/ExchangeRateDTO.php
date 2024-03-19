<?php

namespace App\DTO;

use Illuminate\Http\Request;

class ExchangeRateDTO
{
    public function __construct(public string $date, public float $value)
    {
    }

    public static function fromRequest(Request $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('value')
        );
    }
}
