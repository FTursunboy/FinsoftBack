<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\StorageSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\EmployeeSalePlanRequest;
use App\Http\Requests\Api\Plan\StorageSalePlanRequest;
use App\Http\Resources\Plan\EmployeeSalePlanResource;
use App\Http\Resources\Plan\StorageSalePlanResource;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;
use App\Repositories\Plans\Contracts\StorageSaleRepositoryInterface;
use App\Traits\ApiResponse;

class StorageSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public StorageSaleRepositoryInterface $repository) { }

    public function store(StorageSalePlanRequest $request)
    {
        return $this->created(StorageSalePlanResource::make($this->repository->store(StorageSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(StorageSalePlanResource::make($plan->load(['storageSalePlan.month', 'storageSalePlan.storage', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(StorageSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(StorageSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(StorageSalePlanResource::make($this->repository->update(StorageSalePlanDTO::fromRequest($request), $plan)));
    }

}
