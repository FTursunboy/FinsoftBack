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


    public function index(int $id, array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Group::where('type', $id);

        $relation = $id === 1 ? 'users' : 'storages';

        $query = $this->sort($filterParams, $query, [$relation, $relation . '.group', $relation . '.organization']);

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

}
