<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\DTO\CashStore\SalaryPaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\AccountablePersonRefundRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\SalaryPaymentRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
use App\Repositories\Contracts\CashStore\SalaryPaymentRepositoryInterface;
use App\Traits\ApiResponse;

class SalaryPaymentController extends Controller
{
    use  ApiResponse;

    public function __construct(public SalaryPaymentRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(SalaryPaymentRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(SalaryPaymentDTO::fromRequest($request))));
    }

    public function update(CashStore $cashStore, SalaryPaymentRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->update($cashStore, SalaryPaymentDTO::fromRequest($request))));
    }
}
