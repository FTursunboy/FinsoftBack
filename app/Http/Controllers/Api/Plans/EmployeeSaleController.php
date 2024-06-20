<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\EmployeeSalePlanRequest;
use App\Http\Resources\Plan\EmployeeSalePlanResource;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;
use App\Traits\ApiResponse;

class EmployeeSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public EmployeeSaleRepositoryInterface $repository) { }

    public function store(EmployeeSalePlanRequest $request)
    {
        return $this->created(EmployeeSalePlanResource::make($this->repository->store(EmployeeSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(EmployeeSalePlanResource::make($plan->load(['salePlan.month', 'salePlan.employee', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(EmployeeSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(EmployeeSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(EmployeeSalePlanResource::make($this->repository->update(EmployeeSalePlanDTO::fromRequest($request), $plan)));
    }

}
