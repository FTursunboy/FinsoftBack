<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\UserResource;
use App\Models\Currency;
use App\Models\User;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(UserRepositoryInterface $repository)
    {
        return $this->success(UserResource::collection($repository->index()));
    }

    public function show(User $user) :JsonResponse
    {
        return $this->success(UserResource::make($user));
    }

    public function store(UserRepositoryInterface $repository, UserRequest $request)
    {
        return $this->created($repository->store(UserDTO::fromRequest($request)));
    }

    public function update(User $user, UserRequest $request, UserRepositoryInterface $repository)
    {
        return $this->success(UserResource::make($repository->update($user, UserDTO::fromRequest($request))));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new User(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new User(), $request->validated()));
    }
}
