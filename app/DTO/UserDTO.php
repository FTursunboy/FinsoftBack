<?php

namespace App\DTO;

use App\Http\Requests\Api\User\UserRequest;
use Illuminate\Http\UploadedFile;

class UserDTO
{
    public function __construct(public string $name, public int $organization_id, public ?UploadedFile $image,
                public string $login, public string $password, public string $phone, public string $email, public int $group_id)
    {
    }

    public static function fromRequest(UserRequest $request): self
    {
        return new static(
            $request->get('name'),
            $request->get('organization_id'),
            $request->file('image'),
            $request->get('login'),
            $request->get('password'),
            $request->get('phone'),
            $request->get('email'),
            $request->get('group_id')
        );
    }
}
