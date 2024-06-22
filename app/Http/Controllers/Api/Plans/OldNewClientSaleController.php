<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\OldNewClientSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\OldNewClientPlanRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Plan\OldNewClientSalePlanResource;
use App\Models\OldNewClientPlan;
use App\Models\SalePlan;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Plans\Contracts\OldNewClientSaleRepositoryInterface;
use App\Traits\ApiResponse;

class OldNewClientSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public OldNewClientSaleRepositoryInterface $repository) { }

    public function store(OldNewClientPlanRequest $request)
    {
        return $this->created(OldNewClientSalePlanResource::make($this->repository->store(OldNewClientSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(OldNewClientSalePlanResource::make($plan->load(['salePlan.month', 'salePlan.employee', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(OldNewClientSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(OldNewClientPlanRequest $request, SalePlan $plan)
    {
        return $this->success(OldNewClientSalePlanResource::make($this->repository->update(OldNewClientSalePlanDTO::fromRequest($request), $plan)));
    }

    public function massDelete(IdRequest $request)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new OldNewClientPlan(), $request->validated()));
    }
}
