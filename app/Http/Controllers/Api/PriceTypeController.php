<?php

namespace App\Http\Controllers\Api;

use App\DTO\PriceTypeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\PriceTypeRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\PriceTypeResource;
use App\Models\PriceType;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\PriceTypeRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PriceTypeController extends Controller
{
    use ApiResponse;

    public function __construct(public PriceTypeRepository $repository)
    {
    }

    public function index(IndexRequest $request) :JsonResponse
    {
        return $this->paginate(PriceTypeResource::collection($this->repository->index($request->validated())));
    }

    public function show(PriceType $priceType) :JsonResponse
    {
        return $this->success(PriceTypeResource::make($priceType->load('currency')));
    }

    public function store(PriceTypeRequest $request) :JsonResponse
    {
        return $this->created($this->repository->store(PriceTypeDTO::fromRequest($request)));
    }

    public function update(PriceType $priceType, PriceTypeRequest $request) :JsonResponse
    {
        return $this->success(PriceTypeResource::make($this->repository->update($priceType, PriceTypeDTO::fromRequest($request))));
    }

    public function destroy(PriceType $priceType) :JsonResponse
    {
        return $this->deleted($priceType->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new PriceType(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new PriceType(), $request->validated()));
    }
}
