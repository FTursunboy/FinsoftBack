<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\DTO\CashStore\CreditReceiveDTO;
use App\DTO\CashStore\InvestmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\AnotherCashRegisterRequest;
use App\Http\Requests\Api\CashStore\CreditReceiveRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\InvestmentRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Repositories\Contracts\CashStore\CreditReceiveRepositoryInterface;
use App\Repositories\Contracts\CashStore\InvestmentRepositoryInterface;
use App\Traits\ApiResponse;

class CreditReceiveController extends Controller
{
    use  ApiResponse;

    public function __construct(public CreditReceiveRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(CreditReceiveRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(CreditReceiveDTO::fromRequest($request))));
    }

    public function update(CashStore $cashStore, CreditReceiveRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->upate($cashStore, CreditReceiveDTO::fromRequest($request))));
    }
}
