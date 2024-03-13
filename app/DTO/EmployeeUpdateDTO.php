<?php

namespace App\DTO;

use App\Http\Requests\Api\Employee\EmployeeRequest;
use App\Http\Requests\Api\Employee\EmployeeUpdateRequest;
use Illuminate\Http\UploadedFile;

class EmployeeUpdateDTO
{
    public function __construct(public string $name, public ?UploadedFile $image,
                    public ?string $phone, public ?string $email, public ?string $address)
    {
    }

    public static function fromRequest(EmployeeUpdateRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->file('image'),
            $request->string('phone'),
            $request->string('email'),
            $request->string('address'),
        );
    }
}
