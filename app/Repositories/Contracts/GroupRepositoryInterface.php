<?php

namespace App\Repositories\Contracts;

use App\DTO\GroupDTO;
use App\DTO\GroupUpdateDTO;
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

    public function update(Group $group, GroupUpdateDTO $DTO) :Group;

    public function exportEmployees(Group $group, array $data);

    public function exportUsers(Group $group, array $data);

    public function exportStorages(Group $group, array $data);
}
