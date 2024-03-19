<?php

namespace App\DTO;

use App\Http\Requests\Api\PriceTypeRequest;

class PriceTypeDTO
{
    public function __construct(public string $name, public int $currency_id, public ?string $description)
    {
    }

    public static function fromRequest(PriceTypeRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('currency_id'),
            $request->get('description')
        );
    }
}
