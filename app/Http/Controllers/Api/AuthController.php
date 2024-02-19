<?php

namespace App\Http\Controllers\Api;

use App\DTO\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Repositories\AuthRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(public AuthRepository $repository)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->repository->checkLogin(LoginDTO::fromRequest($request));

        if (! $user) {
            throw ValidationException::withMessages(['message' => __('auth.failed')]);
        }

        return response()->json([
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        return $this->deleted(auth()->user()->tokens()->delete());
    }
}
