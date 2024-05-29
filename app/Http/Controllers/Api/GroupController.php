<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Group\FilterRequest;
use App\Http\Requests\Api\Group\GroupRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\StorageResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
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
        $users = $group->users->where('deleted_at', null);
        $storages = $group->storages->where('deleted_at', null);
        $employees = $group->employees->where('deleted_at', null);
        if ($users->isNotEmpty() || $storages->isNotEmpty() || $employees->isNotEmpty()) {
            abort(400, 'В этой группе есть данные!');
        }
        return $this->deleted($group->delete());
    }

    public function restore(Group $group)
    {
        return $this->success($group->update(['deleted_at' => null]));
    }

    public function exportEmployees(Group $group,FilterRequest $request)
    {
        return response()->download($this->repository->exportEmployees($group, $request->validated()))->deleteFileAfterSend();
    }

    public function exportUsers(Group $group, FilterRequest $request)
    {
        return response()->download($this->repository->exportUsers($group, $request->validated()))->deleteFileAfterSend();
    }

    public function exportStorages(Group $group, FilterRequest $request)
    {
        return response()->download($this->repository->exportStorages($group, $request->validated()))->deleteFileAfterSend();
    }
}
