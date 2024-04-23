<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\OtherIncomesDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\OtherIncomesRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CheckingAccount\OtherIncomesRepositoryInterface;
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
}
