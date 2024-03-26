<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Group\FilterRequest;
use App\Http\Requests\Api\Group\GroupRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Traits\ApiResponse;

class GroupController extends Controller
{
    use ApiResponse;

    public function __construct(public GroupRepositoryInterface $repository)
    {
    }

    public function usersGroup(IndexRequest $request)
    {
        return $this->paginate(GroupResource::collection($this->repository->usersGroup($request->validated())));
    }

    public function storagesGroup(IndexRequest $request)
    {
        return $this->paginate(GroupResource::collection($this->repository->storagesGroup($request->validated())));
    }

    public function employeesGroup(IndexRequest $request)
    {
        return $this->paginate(GroupResource::collection($this->repository->employeesGroup($request->validated())));
    }

    public function getUsers(Group $group, FilterRequest $request)
    {
        return $this->paginate(UserResource::collection($this->repository->getUsers($group, $request->validated())));
    }

    public function getStorages(Group $group, FilterRequest $request)
    {
        return $this->paginate(StorageResource::collection($this->repository->getStorages($group, $request->validated())));
    }


    public function getEmployees(Group $group, FilterRequest $request)
    {
        return $this->paginate(EmployeeResource::collection($this->repository->getEmployees($group, $request->validated())));
    }

    public function store(GroupRequest $request)
    {
        return $this->created(GroupResource::make($this->repository->store(GroupDTO::fromRequest($request))));
    }

    public function update(Group $group, GroupRequest $request)
    {
        return $this->success(GroupResource::make($this->repository->update($group, GroupDTO::fromRequest($request))));
    }

    public function show(Group $group) {
        return $this->success(GroupResource::make($group));
    }

    public function destroy(Group $group)
    {
        return $this->deleted($group->delete());
    }
}
