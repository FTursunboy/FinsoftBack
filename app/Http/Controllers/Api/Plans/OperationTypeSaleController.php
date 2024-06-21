<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\OperationTypeSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\OperationTypeSalePlanRequest;
use App\Http\Resources\Plan\OperationTypeSalePlanResource;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\OperationTypeSaleRepositoryInterface;
use App\Traits\ApiResponse;

class OperationTypeSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public OperationTypeSaleRepositoryInterface $repository) { }

    public function store(OperationTypeSalePlanRequest $request)
    {
        return $this->created(OperationTypeSalePlanResource::make($this->repository->store(OperationTypeSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(OperationTypeSalePlanResource::make($plan->load(['salePlan.month', 'salePlan.employee', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(OperationTypeSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(OperationTypeSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(OperationTypeSalePlanResource::make($this->repository->update(OperationTypeSalePlanDTO::fromRequest($request), $plan)));
    }

}
