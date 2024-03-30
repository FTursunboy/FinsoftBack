<?php

namespace App\Repositories;

use App\DTO\CategoryDTO;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Category::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['']);

        return $query->paginate($filterParams['itemsPerPage']);
    }


    public function store(CategoryDTO $DTO)
    {
        return Category::create([
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

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }
}
