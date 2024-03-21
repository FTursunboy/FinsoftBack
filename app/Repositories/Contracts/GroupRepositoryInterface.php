<?php

namespace App\Repositories\Contracts;

use App\DTO\GroupDTO;
use App\Models\Group;
use Illuminate\Pagination\LengthAwarePaginator;

interface GroupRepositoryInterface
{
    public function usersGroup(array $data) :LengthAwarePaginator;

    public function storagesGroup(array $data) :LengthAwarePaginator;

    public function employeesGroup(array $data) :LengthAwarePaginator;

    public function getUsers(Group $group, array $data) :LengthAwarePaginator;

    public function getStorages(Group $group, array $data) :LengthAwarePaginator;

    public function getEmployees(Group $group, array $data) :LengthAwarePaginator;

    public function store(GroupDTO $DTO);

    public function update(Group $group, GroupDTO $DTO) :Group;
}
