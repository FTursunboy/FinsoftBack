<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CashStore\FilterRequest;
use App\Http\Resources\CashStoreResource;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;

class WithdrawalController extends Controller
{
    public function __construct(public CashStoreRepositoryInterface $repository) {}

    public function index(FilterRequest $request)
    {
        return CashStoreResource::collection($this->repository->index($request));
    }

    public function store()
    {

    }
}
