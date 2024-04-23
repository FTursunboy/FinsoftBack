<?php

namespace App\Repositories;

use App\DTO\CategoryDTO;
use App\Models\Category;
use App\Models\Department;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DepartmentRepository implements CategoryRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Department::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData();

        $query = $this->model::filter($filterParams);

        return $query->paginate($filterParams['itemsPerPage']);
    }


    public function store(CategoryDTO $DTO)
    {
        return $this->model::create([
            'name' => $DTO->name,
        ]);
    }

    public function update(Category $category, CategoryDTO $DTO) :Category
    {
        $category->update([
            'name' => $DTO->name,
        ]);

        return $category;
    }

}
