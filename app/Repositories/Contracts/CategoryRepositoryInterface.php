<?php

namespace App\Repositories\Contracts;

use App\DTO\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface extends IndexInterface
{
    public function store(CategoryDTO $DTO);

    public function update(Category $category, CategoryDTO $DTO) :Category;
}
