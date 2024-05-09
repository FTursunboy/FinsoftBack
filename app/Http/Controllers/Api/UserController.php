<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\User\ChangePasswordRequest;
use App\Http\Requests\Api\User\FcmTokenRequest;
use App\Http\Requests\Api\User\FilterRequest;
use App\Http\Requests\Api\User\UserRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;


    public function __construct(public UserRepositoryInterface $repository)
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(UserResource::collection($this->repository->index($request->validated())));
    }

    public function show(User $user): JsonResponse
    {
        return $this->success(UserResource::make($user));
    }

    public function store(UserRepositoryInterface $repository, UserRequest $request)
    {
        return $this->created($repository->store(UserDTO::fromRequest($request)));
    }

    public function update(User $user, UserUpdateRequest $request, UserRepositoryInterface $repository)
    {
        return $this->success(UserResource::make($repository->update($user, UserUpdateDTO::fromRequest($request))));
    }

    public function changePassword(User $user, ChangePasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::where('id', $user->id);

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return $this->success('Пароль успешно изменен!');
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->deleted($delete->massDelete(new User(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new User(), $request->validated()));
    }

    public function deleteImage(User $user)
    {
        return $this->deleted($this->repository->deleteImage($user));
    }

    public function documentAuthors(FilterRequest $request)
    {
        return $this->paginate(UserResource::collection($this->repository->documentAuthors($request->validated())));
    }

    public function addFcmToken(FcmTokenRequest $request)
    {
        return $this->success(auth()->user()->update(['fcm_token' => $request->fcm_token]));
    }
}
