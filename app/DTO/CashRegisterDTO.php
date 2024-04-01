<?php

namespace App\DTO;

use App\Http\Requests\Api\CashRegister\CashRegisterRequest;
use Illuminate\Http\Request;

class CashRegisterDTO
{
    public function __construct(public string $name, public int $currency_id, public int $organization_id, public int $responsible_person_id)
    {
    }

    public static function fromRequest(Request $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('currency_id'),
            $request->get('organization_id'),
            $request->get('responsible_person_id'),
        );
    }
}
