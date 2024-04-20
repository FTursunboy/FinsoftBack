<?php

namespace App\Http\Controllers\Api;

use App\DTO\WithdrawalDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\WithdrawalRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CashStore\WithdrawalRepositoryInterface;
use App\Traits\ApiResponse;

class WithdrawalController extends Controller
{
    use  ApiResponse;

    public function __construct(public WithdrawalRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return CashStoreResource::collection($this->repository->index($request->validated()));
    }

    public function store(WithdrawalRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(WithdrawalDTO::fromRequest($request))));
    }
}
