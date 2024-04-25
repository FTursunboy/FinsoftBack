<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\CreditReceiveDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\CreditReceiveRequest;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\CreditReceiveRepositoryInterface;
use App\Traits\ApiResponse;

class CreditReceiveController extends Controller
{
    use  ApiResponse;

    public function __construct(public CreditReceiveRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(CreditReceiveRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(CreditReceiveDTO::fromRequest($request))));
    }

    public function update(CreditReceiveRequest $request, CheckingAccount $account)
    {
        return $this->success($this->repository->update(CreditReceiveDTO::fromRequest($request), $account));
    }
}
