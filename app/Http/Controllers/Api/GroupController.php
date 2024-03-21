<?php

namespace App\Http\Controllers\Api;

use App\DTO\GroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Group\GroupRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Traits\ApiResponse;

class GroupController extends Controller
{
    use ApiResponse;

    public function __construct(public GroupRepositoryInterface $repository)
    {
    }

    public function index(int $id, IndexRequest $request)
    {
        return $this->paginate(GroupResource::collection($this->repository->index($id, $request->validated())));
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
