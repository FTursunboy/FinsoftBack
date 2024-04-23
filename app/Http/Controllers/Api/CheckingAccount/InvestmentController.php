<?php

namespace App\Http\Controllers\Api\CheckingAccount;

use App\DTO\CheckingAccount\InvestmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckingAccount\FilterRequest;
use App\Http\Requests\Api\CheckingAccount\InvestmentRequest;
use App\Http\Resources\CheckingAccountResource;
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
}
