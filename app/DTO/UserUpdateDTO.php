<?php

namespace App\DTO;

use App\Http\Requests\Api\User\UserRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use Illuminate\Http\UploadedFile;

class UserUpdateDTO
{
    public function __construct(public string $name, public int $organization_id, public ?UploadedFile $image,
                public string $login, public string $phone, public string $email, public bool $status, public int $group_id)
    {
    }

    public static function fromRequest(UserUpdateRequest $request): self
    {
        return new static(
            $request->get('name'),
            $request->get('organization_id'),
            $request->file('image'),
            $request->get('login'),
            $request->get('phone'),
            $request->get('email'),
            $request->get('status'),
            $request->get('group_id'),
        );
    }
}
