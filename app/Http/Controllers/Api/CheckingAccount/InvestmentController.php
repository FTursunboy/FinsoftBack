<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\InvestmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\InvestmentRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\InvestmentRepositoryInterface;
use App\Traits\ApiResponse;

class InvestmentController extends Controller
{
    use  ApiResponse;

    public function __construct(public InvestmentRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(InvestmentRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(InvestmentDTO::fromRequest($request))));
    }

    public function update(InvestmentRequest $request, CheckingAccount $account) {

        return $this->success($this->repository->update(InvestmentDTO::fromRequest($request), $account));

    }
}
