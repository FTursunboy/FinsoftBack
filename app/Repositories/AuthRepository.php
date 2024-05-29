<?php

namespace App\Repositories;

use App\DTO\LoginDTO;
use App\Models\User;
use App\Models\VerificationCode;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function checkLogin(LoginDTO $dto) :User|null
    {
        $user = User::where('login', $dto->login)->first();

        if ($user && Auth::attempt(['login' => $dto->login, 'password' => $dto->password]) && $user->status === 1 && $user->deleted_at === null) {
            return $user->load('permissions', 'organization');
        }

        return null;
    }

    public function forgotPassword(string $phone): string
    {
        $user = User::getByPhone($phone)->first();

        $code = rand(1, 9999);

        //todo HTTP REQUEST TO SEND SMS


        VerificationCode::create([
            'code' => $code,
            'user_id' => $user->id,
        ]);

        return $code;
    }
}
