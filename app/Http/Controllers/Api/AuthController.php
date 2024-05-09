<?php

namespace App\Http\Controllers\Api;

use App\DTO\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePinRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PinRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'pin' => $user->pin,
            'fcm_token' => $user->fcm_token ? true : false
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->deleted();
    }

    public function addPin(PinRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $user->update(['pin' => $data['pin']]);

        return $this->success();
    }

    public function loginWithPin(LoginRequest $request)
    {
        $DTO = LoginDTO::fromRequest($request);

        $user = $this->repository->checkLogin($DTO);

        if ($user?->pin !== $DTO->pin || $user?->pin === null) return $this->error('Неправильный пин-код!');

        return response()->json([
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'user' => UserResource::make($user),
            'pin' => $user->pin
        ]);
    }

    public function changePin(ChangePinRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();

        if ($data['oldPin'] !== $user->pin) {
            return $this->error('Старый пин-код не правильный!');
        }

        $user->update(['pin' => $data['pin']]);

        return $this->success('Пин-код успешно изменен!');
    }
}
