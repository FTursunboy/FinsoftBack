<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\AnotherCashRegisterRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CashStore\AnotherCashRegisterRepositoryInterface;
use App\Traits\ApiResponse;

class AnotherCashRegisterController extends Controller
{
    use  ApiResponse;

    public function __construct(public AnotherCashRegisterRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(AnotherCashRegisterRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(AnotherCashRegisterDTO::fromRequest($request))));
    }
}
