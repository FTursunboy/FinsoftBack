<?php

namespace App\Repositories;

use App\DTO\GoodDTO;
use App\DTO\GoodGroupDTO;
use App\DTO\GoodUpdateDTO;
use App\Models\Good;
use App\Models\GoodGroup;
use App\Models\GoodImages;
use App\Models\Group;
use App\Models\User;
use App\Repositories\Contracts\GoodGroupRepositoryInterface;
use App\Repositories\Contracts\GoodRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodGroupRepository implements GoodGroupRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = GoodGroup::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $goodQuery = Good::query();

        $goodQuery = $this->filter($goodQuery, $filterParams);
        $goodIds = $this->searchUsers($goodQuery, $filterParams['search'])->pluck('id')->toArray();


        $query = $this->model::whereHas('goods', function ($query) use ($goodIds) {
                $query->whereIn('goods.id', $goodIds);
            })
            ->with(['goods' => function ($query) use ($goodIds) {
                $query->whereIn('goods.id', $goodIds)->with('group');
            }]);

        $query = $this->sort($filterParams, $query, []);

        $groups = $query->paginate($filterParams['itemsPerPage']);

        foreach ($groups as $group) {
            $filteredUsers = $group->users->filter(function ($user) use ($goodIds) {
                return in_array($user->id, $goodIds);
            });
            $group->setRelation('users', $filteredUsers);
        }

        return $groups;
    }

    public function filter($query, array $data)
    {
        return $query->when($data['storage_id'], function ($query) use ($data) {
            return $query->where('storage_id', $data['storage_id']);
        })
            ->when($data['unit_id'], function ($query) use ($data) {
                return $query->where('unit_id', $data['unit_id']);
            })
            ->when($data['description'], function ($query) use ($data) {
                return $query->where('description', 'like', '%' . $data['description'] . '%');
            })
            ->when($data['vendor_code'], function ($query) use ($data) {
                return $query->where('vendor_code', 'like', '%' . $data['vendor_code'] . '%');
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            });
    }

    public function search($query, string $search)
    {
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('email', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('login', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('phone', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('group', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ;
        });
    }

    public function store(GoodGroupDTO $DTO)
    {
        $good = GoodGroup::create([
            'name' => $DTO->name,
            'is_good' => $DTO->is_good ?? false,
            'is_service' => $DTO->is_service ?? false,
        ]);

        return $good;
    }

    public function update(GoodGroup $goodGroup, GoodGroupDTO $DTO)
    {
        $goodGroup->update([
            'name' => $DTO->name,
            'is_good' => $DTO->is_good ?? false,
            'is_service' => $DTO->is_service ?? false,
        ]);

        return $goodGroup;
    }

    public function getGoods(GoodGroup $goodGroup, array $data)
    {
        $filterParams = Good::filter($data);

        $query = $this->searchGood($filterParams['search'], $goodGroup);

        $query = $this->filterGood($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['goodGroup', 'storage', 'unit']);

        return $query->paginate($filterParams['itemsPerPage']);
    }



}
