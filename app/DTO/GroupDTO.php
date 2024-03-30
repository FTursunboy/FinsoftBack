<?php

namespace App\DTO;

use App\Http\Requests\Api\CashRegister\CashRegisterRequest;
use App\Http\Requests\Api\Group\GroupRequest;
use Illuminate\Http\Request;

class GroupDTO
{
    public function __construct(public string $name, public int $type)
    {
    }

    public static function fromRequest(GroupRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('type')
        );
    }
}
