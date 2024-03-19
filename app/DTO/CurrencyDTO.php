<?php

namespace App\DTO;

use App\Http\Requests\Api\Currency\CurrencyRequest;

class CurrencyDTO
{
    public function __construct(public string $name, public int $digital_code, public string $symbol_code)
    {
    }

    public static function fromRequest(CurrencyRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('digital_code'),
            $request->get('symbol_code')
        );
    }
}
