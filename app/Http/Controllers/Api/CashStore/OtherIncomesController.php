<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\OtherExpensesDTO;
use App\DTO\CashStore\OtherIncomesDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\OtherExpensesRequest;
use App\Http\Requests\Api\CashStore\OtherIncomesRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;
use App\Repositories\Contracts\CashStore\OtherExpensesRepositoryInterface;
use App\Repositories\Contracts\CashStore\OtherIncomesRepositoryInterface;
use App\Traits\ApiResponse;

class OtherIncomesController extends Controller
{
    use  ApiResponse;

    public function __construct(public OtherIncomesRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(OtherIncomesRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(OtherIncomesDTO::fromRequest($request))));
    }

    public function update(CashStore $cashStore, OtherIncomesRequest $request)
    {
        return $this->success(CashStoreResource::make($this->repository->update($cashStore, OtherIncomesDTO::fromRequest($request))));
    }
}
