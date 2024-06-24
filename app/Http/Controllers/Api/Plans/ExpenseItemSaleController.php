<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\Plan\ExpenseItemSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Plan\ExpenseItemSalePlanRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Plan\ExpenseItemSalePlanResource;
use App\Models\ExpenseItemPlan;
use App\Models\SalePlan;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Plans\Contracts\ExpenseItemSaleRepositoryInterface;
use App\Traits\ApiResponse;

class ExpenseItemSaleController extends Controller
{
    use ApiResponse;

    public function __construct(public ExpenseItemSaleRepositoryInterface $repository) { }

    public function store(ExpenseItemSalePlanRequest $request)
    {
        return $this->created(ExpenseItemSalePlanResource::make($this->repository->store(ExpenseItemSalePlanDTO::fromRequest($request))));
    }

    public function show(SalePlan $plan)
    {
        return $this->success(ExpenseItemSalePlanResource::make($plan->load(['salePlan.month', 'salePlan.employee', 'organization'])));
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(ExpenseItemSalePlanResource::collection($this->repository->index($request->validated())));
    }

    public function update(ExpenseItemSalePlanRequest $request, SalePlan $plan)
    {
        return $this->success(ExpenseItemSalePlanResource::make($this->repository->update(ExpenseItemSalePlanDTO::fromRequest($request), $plan)));
    }

    public function massDelete(IdRequest $request)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new ExpenseItemPlan(), $request->validated()));
    }

}
