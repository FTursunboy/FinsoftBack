<?php

namespace App\DTO;

use App\Http\Requests\Api\User\FcmTokenRequest;

class FcmTokenDTO
{
    public function __construct(public string $fcm_token, public string $device)
    {
    }

    public static function fromRequest(FcmTokenRequest $request): self
    {
        return new static(
            $request->get('fcm_token'),
            $request->get('device'),
        );
    }
}
