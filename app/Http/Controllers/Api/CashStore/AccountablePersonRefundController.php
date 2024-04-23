<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\DTO\CashStore\CreditReceiveDTO;
use App\DTO\CashStore\InvestmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\AccountablePersonRefundRequest;
use App\Http\Requests\Api\CashStore\AnotherCashRegisterRequest;
use App\Http\Requests\Api\CashStore\CreditReceiveRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\InvestmentRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Repositories\Contracts\CashStore\CreditReceiveRepositoryInterface;
use App\Repositories\Contracts\CashStore\InvestmentRepositoryInterface;
use App\Traits\ApiResponse;

class AccountablePersonRefundController extends Controller
{
    use  ApiResponse;

    public function __construct(public AccountablePersonRefundRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(AccountablePersonRefundRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(AccountablePersonRefundDTO::fromRequest($request))));
    }
}
