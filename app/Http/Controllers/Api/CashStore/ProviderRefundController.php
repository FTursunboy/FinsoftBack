<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\ProviderRefundDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\ProviderRefundRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\ProviderRefundRepositoryInterface;
use App\Traits\ApiResponse;

class ProviderRefundController extends Controller
{
    use  ApiResponse;

    public function __construct(public ProviderRefundRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(ProviderRefundRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(ProviderRefundDTO::fromRequest($request))));
    }

    public function update(CashStore $cashStore, ProviderRefundRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->update($cashStore, ProviderRefundDTO::fromRequest($request))));
    }
}
