<?php

namespace App\DTO;

use App\Http\Requests\Api\Organization\OrganizationRequest;
use App\Http\Requests\Api\Organization\OrganizationUpdateRequest;
use Illuminate\Http\Request;

class OrganizationUpdateDTO
{
    public function __construct(public string $name, public string $address, public ?string $description, public int $INN, public int $director_id, public int $chief_accountant_id)
    {
    }

    public static function fromRequest(OrganizationUpdateRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('address'),
            $request->get('description'),
            $request->get('INN'),
            $request->get('director_id'),
            $request->get('chief_accountant_id'),
        );
    }
}
