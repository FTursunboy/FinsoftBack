<?php

namespace App\Repositories;

use App\DTO\OrganizationDTO;
use App\DTO\UnitDTO;
use App\Models\Group;
use App\Models\Organization;
use App\Models\Unit;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UnitRepository implements UnitRepositoryInterface
{
    public $model = Unit::class;

    use Sort, FilterTrait;

    public function index(array $data) :LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->filter($filterParams, $query);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(UnitDTO $DTO)
    {
        return Unit::create([
            'name' => $DTO->name,
        ]);
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }

    public function filter(array $data, $query) :array
    {
        return $query->when($data['name'], function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        });
    }

    public function update(Unit $unit, UnitDTO $DTO) :Unit
    {
        $unit->update([
            'name' => $DTO->name,
        ]);

        return $unit;
    }
}
