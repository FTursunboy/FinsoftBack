<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Report\FilterRequest;
use App\Http\Resources\GoodAccountingResource;
use App\Http\Resources\GoodReportResource;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Traits\ApiResponse;

class GoodReportController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodReportRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(GoodReportResource::collection($this->repository->index($request->validated())));
    }

}
