<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\GroupDTO;
use App\Models\Barcode;
use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupRepository implements GroupRepositoryInterface
{

    public $model = Group::class;
    use Sort, FilterTrait;

    public function usersGroup(array $data) :LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::USERS);

        $query = $this->searchGroup($query, $filterParams['search']);

        $query = $this->sort($filterParams, $query, ['users']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function storagesGroup(array $data) :LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::STORAGES);

        $query = $this->searchGroup($query, $filterParams['search']);

        $query = $this->sort($filterParams, $query, ['storages']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getUsers(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::USERS);

        $query = $this->sort($filterParams, $query, ['users.organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getStorages(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', Group::STORAGES);

        $query = $this->sort($filterParams, $query, ['storages.employeeStorage', 'storages.organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(GroupDTO $DTO)
    {
        return Group::create([
            'name' => $DTO->name,
            'type' => $DTO->type
        ]);
    }

    public function update(Group $group, GroupDTO $DTO) :Group
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

    public function search(array $data)
    {
        return $this->model::where('name', 'like', '%' . $data['search'] . '%')
            ->where(function ($query) use ($data) {
                $query->orWhereHas('currency', function ($query) use ($data) {
                    return $query->where('name', 'like', '%' . $data['search'] . '%');
                })
                    ->orWhereHas('organization', function ($query) use ($data) {
                        return $query->where('name', 'like', '%' . $data['search'] . '%');
                    })
                    ->orWhereHas('responsiblePerson', function ($query) use ($data) {
                        return $query->where('name', 'like', '%' . $data['search'] . '%');
                    });
            });
    }



}
