<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\InstallmentSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\InstallmentSalePlanRequest;
use App\Http\Resources\Plan\InstallmentSalePlanResource;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\InstallmentSaleRepositoryInterface;
use App\Traits\ApiResponse;

class InstallmentSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public InstallmentSaleRepositoryInterface $repository) { }

    public function store(InstallmentSalePlanRequest $request)
    {
        return $this->created(InstallmentSalePlanResource::make($this->repository->store(InstallmentSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(InstallmentSalePlanResource::make($plan->load(['installmentSalePlan.month', 'installmentSalePlan.good', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(InstallmentSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(InstallmentSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(InstallmentSalePlanResource::make($this->repository->update(InstallmentSalePlanDTO::fromRequest($request), $plan)));
    }

}
