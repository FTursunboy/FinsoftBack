<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\ProviderRefundDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\ProviderRefundRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Repositories\Contracts\CheckingAccount\ProviderRefundRepositoryInterface;
use App\Traits\ApiResponse;

class ProviderRefundController extends Controller
{
    use  ApiResponse;

    public function __construct(public ProviderRefundRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(ProviderRefundRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(ProviderRefundDTO::fromRequest($request))));
    }
}
