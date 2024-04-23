<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\WithdrawalDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\WithdrawalRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Repositories\Contracts\CheckingAccount\WithdrawalRepositoryInterface;
use App\Traits\ApiResponse;

class WithdrawalController extends Controller
{
    use  ApiResponse;

    public function __construct(public WithdrawalRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(WithdrawalRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(WithdrawalDTO::fromRequest($request))));
    }
}
