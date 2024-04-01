<?php

namespace App\Http\Controllers\Api;

use App\DTO\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategoryRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Currency;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(public CategoryRepositoryInterface $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(CategoryResource::collection($this->repository->index($request->validated())));
    }

    public function show(Category $category) :JsonResponse
    {
        return $this->success(CategoryResource::make($category));
    }

    public function store(CategoryRequest $request, CategoryRepository $repository)
    {
        return $this->created(CategoryResource::make($repository->store(CategoryDTO::fromRequest($request))));
    }

    public function update(Category $category, CategoryRequest $request, CategoryRepository $repository)
    {
        return $this->success(CategoryResource::make($repository->update($category, CategoryDTO::fromRequest($request))));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Category(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Category(), $request->validated()));
    }
}
