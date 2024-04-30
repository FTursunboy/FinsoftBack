<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\AccountablePersonRefundRequest;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\AccountablePersonRefundRepositoryInterface;
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

    public function update(CashStore $cashStore, AccountablePersonRefundRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->update($cashStore, AccountablePersonRefundDTO::fromRequest($request))));
    }
}
