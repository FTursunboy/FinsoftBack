<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\OtherExpensesDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\OtherExpensesRequest;
use App\Http\Resources\CheckingAccountResource;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\OtherExpensesRepositoryInterface;
use App\Traits\ApiResponse;

class OtherExpensesController extends Controller
{
    use  ApiResponse;

    public function __construct(public OtherExpensesRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CheckingAccountResource::collection($this->repository->index($request->validated())));
    }

    public function store(OtherExpensesRequest $request)
    {
        return $this->created(CheckingAccountResource::make($this->repository->store(OtherExpensesDTO::fromRequest($request))));
    }

    public function update(OtherExpensesRequest $request, CheckingAccount $account)
    {
        return $this->created(CheckingAccountResource::make($this->repository->update(OtherExpensesDTO::fromRequest($request), $account)));
    }
}
