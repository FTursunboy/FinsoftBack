<?php

namespace App\Repositories;

use App\DTO\PositionDTO;
use App\Models\Position;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionRepository implements PositionRepositoryInterface
{
    use FilterTrait, Sort;

    public $model = Position::class;

    public function store(PositionDTO $DTO)
    {
        return $this->model::create([
            'name' => $DTO->name,
        ]);
    }

    public function update(Position $position, PositionDTO $DTO): Position
    {
        $position->update([
            'name' => $DTO->name,
        ]);

        return $position;
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }

    public function filter($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', '%'.$data['name'].'%');
        });
    }

}
