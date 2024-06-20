<?php

namespace App\Http\Controllers\Api\Plans;

use App\DTO\GoodSalePlanDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\GoodSalePlanRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\GoodSalePlanResource;
use App\Models\Good;
use App\Models\GoodSalePlan;
use App\Repositories\Contracts\MassOperationInterface;
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

    public function show(GoodSalePlan $plan)
    {
        return $this->success(GoodSalePlanResource::make($plan->load(['goodSalePlan.month', 'goodSalePlan.good', 'organization'])));
    }

    public function index()
    {
        return $this->success(GoodSalePlanResource::collection(GoodSalePlan::with(['goodSalePlan.month', 'goodSalePlan.good', 'organization'])->get()));
    }

    public function update(GoodSalePlanRequest $request, GoodSalePlan $plan)
    {
        return $this->success(GoodSalePlanResource::make($this->repository->update(GoodSalePlanDTO::fromRequest($request), $plan)));
    }

}
