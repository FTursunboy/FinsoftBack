<?php

namespace App\DTO;

use Illuminate\Http\Request;

class CounterpartyDTO
{
    public function __construct(public string $name, public string $address, public string $phone, public string $email, public array $roles)
    {
    }

    public static function fromRequest(Request $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('address'),
            $request->get('phone'),
            $request->get('email'),
            $request->get('roles')
        );
    }
}
