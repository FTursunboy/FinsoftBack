<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\ClientPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\ClientPaymentRequest;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\CashStoreRepositoryInterface;
use App\Traits\ApiResponse;

class ClientPaymentController extends Controller
{
    use ApiResponse;

    public function __construct(public CashStoreRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(ClientPaymentRequest $request)
    {
        return $this->success(CheckingAccountResource::make($this->repository->clientPayment(ClientPaymentDTO::fromRequest($request))));
    }

    public function update(ClientPaymentRequest $request, CheckingAccount $account)
    {
        return $this->success($this->repository->update(ClientPaymentDTO::fromRequest($request), $account));
    }



}
