<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\AccountablePersonRefundDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\AccountablePersonRefundRequest;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\AccountablePersonRefundRepositoryInterface;
use App\Traits\ApiResponse;

class AccountablePersonRefundController extends Controller
{
    use  ApiResponse;

    public function __construct(public AccountablePersonRefundRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(AccountablePersonRefundRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(AccountablePersonRefundDTO::fromRequest($request))));
    }

    public function update(AccountablePersonRefundRequest $request, CheckingAccount $account)
    {
        return $this->success($this->repository->update(AccountablePersonRefundDTO::fromRequest($request), $account));
    }
}
