<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\GoodSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\GoodSalePlanRequest;
use App\Http\Resources\Plan\GoodSalePlanResource;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\GoodSaleRepositoryInterface;
use App\Traits\ApiResponse;

class GoodSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodSaleRepositoryInterface $repository) { }

    public function store(GoodSalePlanRequest $request)
    {
        return $this->created(GoodSalePlanResource::make($this->repository->store(GoodSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(GoodSalePlanResource::make($plan->load(['goodSalePlan.month', 'goodSalePlan.good', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(GoodSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(GoodSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(GoodSalePlanResource::make($this->repository->update(GoodSalePlanDTO::fromRequest($request), $plan)));
    }

}
