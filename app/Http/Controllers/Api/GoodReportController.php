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
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class GoodReportController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodReportRepositoryInterface $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(CategoryResource::collection($this->repository->index($request->validated())));
    }

}
