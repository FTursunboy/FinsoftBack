<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResourceTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\OperationRequest;
use App\Http\Resources\ItemResource;
use App\Models\Resource;
use App\Models\User;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;



class PermissionController extends Controller
{
    use ApiResponse;

    public function __construct(public PermissionRepositoryInterface $repository){}

    public function givePermission(User $user, OperationRequest $request) :JsonResponse
    {
        return $this->success($this->repository->giveAdminPanelPermission($user, $request->validated()));
    }

    public function getPermissions(User $user) :JsonResponse
    {
        return $this->success($this->repository->getPermissions($user, ResourceTypes::AdminPanel));
    }

    public function getResources() :JsonResponse
    {
        return $this->paginate(ItemResource::collection(Resource::paginate(20)));
    }

    public function getDocsPermission(User $user) :JsonResponse
    {
        return $this->success($this->repository->getPermissions($user, ResourceTypes::Document));
    }

    public function getPodSystemPermission(User $user) :JsonResponse
    {
        return $this->success($this->repository->getPermissions($user, ResourceTypes::PodSystem));
    }

    public function givePodsystemPermission(User $user, OperationRequest $request) :JsonResponse
    {
        return $this->success($this->repository->givePodsystemPermission($user, $request->validated()));
    }

}
