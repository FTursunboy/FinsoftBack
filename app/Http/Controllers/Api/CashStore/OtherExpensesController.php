<?php

namespace App\Http\Controllers\Api\CashStore;

use App\DTO\CashStore\OtherExpensesDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Requests\Api\CashStore\OtherExpensesRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CashStore\OtherExpensesRepositoryInterface;
use App\Traits\ApiResponse;

class OtherExpensesController extends Controller
{
    use  ApiResponse;

    public function __construct(public OtherExpensesRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return $this->paginate(CashStoreResource::collection($this->repository->index($request->validated())));
    }

    public function store(OtherExpensesRequest $request)
    {
        return $this->created(CashStoreResource::make($this->repository->store(OtherExpensesDTO::fromRequest($request))));
    }
}
