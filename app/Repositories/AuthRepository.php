<?php

namespace App\Repositories;

use App\DTO\LoginDTO;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function checkLogin(LoginDTO $dto) :User|null
    {
        $user = User::where('login', $dto->login)->first();

        if ($user && Auth::attempt(['login' => $dto->login, 'password' => $dto->password]) && $user->status === 1 && $user->deleted_at === null) {
            return $user->load('permissions');
        }

        return null;
    }

}
