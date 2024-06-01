<?php

namespace App\DTO;

use App\Http\Requests\Api\CashRegister\CashRegisterRequest;
use App\Http\Requests\Api\Group\GroupRequest;
use App\Http\Requests\Api\Group\GroupUpdateRequest;
use Illuminate\Http\Request;

class GroupUpdateDTO
{
    public function __construct(public string $name, public ?int $type)
    {
    }

    public static function fromRequest(GroupUpdateRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('type')
        );
    }
}
