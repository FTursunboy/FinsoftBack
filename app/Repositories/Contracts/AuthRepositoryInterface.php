<?php

namespace App\Repositories\Contracts;

use App\DTO\LoginDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface
{
    public function checkLogin(LoginDTO $dto) :User|null;

    public function forgotPassword(string $phone);

}
