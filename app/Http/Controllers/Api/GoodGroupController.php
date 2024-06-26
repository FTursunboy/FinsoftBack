<?php

namespace App\Http\Controllers\Api;

use App\DTO\GoodGroupDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GoodGroup\FilterRequest;
use App\Http\Requests\Api\GoodGroup\GoodGroupIdsRequest;
use App\Http\Requests\Api\GoodGroup\GoodGroupRequest;
use App\Http\Requests\Api\GoodGroup\IdRequest;
use App\Http\Resources\GoodGroupResource;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodsPriceResource;
use App\Models\GoodGroup;
use App\Repositories\Contracts\GoodGroupRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class GoodGroupController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodGroupRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(GoodGroupResource::collection($this->repository->index($request->validated())));
    }

    public function store(GoodGroupRequest $request)
    {
        return $this->created(GoodGroupResource::make($this->repository->store(GoodGroupDTO::fromRequest($request))));
    }

    public function show(GoodGroup $goodGroup)
    {
        return $this->success(GoodGroupResource::make($goodGroup));
    }

    public function update(GoodGroup $goodGroup, GoodGroupRequest $request)
    {
        return $this->success(GoodGroupResource::make($this->repository->update($goodGroup, GoodGroupDTO::fromRequest($request))));
    }

    public function getGoods(GoodGroup $goodGroup, FilterRequest $request)
    {
        return $this->paginate(GoodResource::collection($this->repository->getGoods($goodGroup, $request->validated())));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->deleted($delete->massDelete(new GoodGroup(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new GoodGroup(), $request->validated()));
    }

    public function goodsPrice(GoodGroupIdsRequest $request)
    {
        return $this->success(GoodsPriceResource::collection($this->repository->goodsPrice($request->validated())));
    }

}
