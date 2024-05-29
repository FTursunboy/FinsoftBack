<?php

namespace App\Repositories;

use App\DTO\GoodDTO;
use App\DTO\GoodGroupDTO;
use App\DTO\GoodUpdateDTO;
use App\Models\Good;
use App\Models\GoodGroup;
use App\Models\GoodImages;
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

        $query = $this->search($filterParams['search']);

        $query = $this->filterGroup($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['goods']);

        return $query->paginate($filterParams['itemsPerPage']);
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

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('goods', function ($query) use ($searchTerm) {
                    return $query->where('goods.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function searchGood(string $search, GoodGroup $goodGroup)
    {
        if (!$search) {
            return Good::where('good_group_id', $goodGroup->id);
        }

        $searchTerms = explode(' ', $search);

        $query = Good::where('good_group_id', $goodGroup->id);

        $query->where(function ($query) use ($searchTerms) {
            $query->orWhere('name', 'like', '%' . implode('%', $searchTerms) . '%');
        });

        return $query;
    }

    public function filterGood($query, array $data)
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

    public function filterGroup($query, array $data)
    {
        return $query->when($data['is_good'], function ($query) use ($data) {
            return $query->where('is_good', $data['is_good']);
        })
            ->when($data['is_service'], function ($query) use ($data) {
                return $query->where('is_service', $data['is_service']);
            })
            ->when($data['good_group_id'], function ($query) use ($data) {
                return $query->where('good_group_id', $data['good_group_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            });
    }
}
