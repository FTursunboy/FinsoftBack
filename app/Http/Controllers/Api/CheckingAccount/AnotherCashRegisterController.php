<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\AnotherCashRegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\AnotherCashRegisterRequest;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\AnotherCashRegisterRepositoryInterface;
use App\Traits\ApiResponse;

class AnotherCashRegisterController extends Controller
{
    use  ApiResponse;

    public function __construct(public AnotherCashRegisterRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(AnotherCashRegisterRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(AnotherCashRegisterDTO::fromRequest($request))));
    }

    public function update(AnotherCashRegisterRequest $request, CheckingAccount $account)
    {
        return $this->success($this->repository->update(AnotherCashRegisterDTO::fromRequest($request), $account));
    }
}
