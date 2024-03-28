<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\GroupDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Storage;
use App\Models\User;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupRepository implements GroupRepositoryInterface
{

    public $model = Group::class;
    use Sort, FilterTrait;

    public function usersGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::USERS);

        $query = $this->searchGroup($query, $filterParams['search']);

        $query = $this->sort($filterParams, $query, ['users']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function storagesGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::STORAGES);

        $query = $this->searchGroup($query, $filterParams['search']);

        $query = $this->sort($filterParams, $query, ['storages']);

        return $query->paginate($filterParams['itemsPerPage']);
    }


    public function employeesGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::EMPLOYEES);

        $query = $this->searchGroup($query, $filterParams['search']);

        $query = $this->sort($filterParams, $query, ['employees']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getUsers(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = User::filter($data);

        $query = User::where('group_id', $group->id);

        $query = $this->searchUser($query, $filterParams);

        $query = $this->filterUser($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getStorages(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = Storage::filter($data);

        $query = Storage::where('group_id', $group->id);

        $query = $this->search($query, $filterParams);

        $query = $this->filterStorage($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['employeeStorage', 'organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getEmployees(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = Employee::filter($data);

        $query = Employee::where('group_id', $group->id);

        $query = $this->search($query, $filterParams);

        $query = $this->filterEmployee($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['position', 'group']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(GroupDTO $DTO)
    {
        return Group::create([
            'name' => $DTO->name,
            'type' => $DTO->type
        ]);
    }

    public function update(Group $group, GroupDTO $DTO): Group
    {
        $group->update([
            'name' => $DTO->name,
        ]);

        return $group;
    }

    public function searchGroup($query, string $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function search($query, array $data)
    {
        return $query->where('name', 'like', '%' . $data['search'] . '%');
    }

    public function searchUser($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'admin');
                });
        });
    }

    public function filterUser($query, array $data)
    {
        return $query->when($data['organization_id'], function ($query) use ($data) {
            return $query->where('organization_id', $data['organization_id']);
        })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['login'], function ($query) use ($data) {
                return $query->where('login', 'like', '%' . $data['login'] . '%');
            })
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            });
    }

    public function filterStorage($query, array $data)
    {
        return $query->when($data['organization_id'], function ($query) use ($data) {
            return $query->where('organization_id', $data['organization_id']);
        })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['employee_id'], function ($query) use ($data) {
                return $query->join('employee_storages', 'employee_storages.storage_id', '=', 'storages.id')
                    ->where([
                        ['employee_storages.employee_id', $data['employee_id']],
                    ])
                    ->select('storages.id', 'storages.name', 'storages.organization_id', 'storages.created_at', 'storages.deleted_at');
            });
    }


    public function filterEmployee($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', '%' . $data['name'] . '%');
        })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            })
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['address'], function ($query) use ($data) {
                return $query->where('address', 'like', '%' . $data['address'] . '%');
            });
    }

}
