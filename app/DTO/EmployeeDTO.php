<?php

namespace App\DTO;

use App\Http\Requests\Api\Employee\EmployeeRequest;
use Illuminate\Http\UploadedFile;

class EmployeeDTO
{
    public function __construct(public string $name, public ?UploadedFile $image,
                    public ?string $phone, public ?string $email, public ?string $address, public int $group_id)
    {
    }

    public static function fromRequest(EmployeeRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->file('image'),
            $request->string('phone'),
            $request->string('email'),
            $request->string('address'),
            $request->get('group_id'),
        );
    }
}
