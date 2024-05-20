<?php

namespace App\Http\Controllers\Api\Report;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Report\FilterRequest;
use App\Http\Resources\Report\CounterpartySettlementResource;
use App\Repositories\Contracts\Report\CounterpartyReportRepositoryInterface;
use App\Traits\ApiResponse;

class CounterpartyReportController extends Controller
{
    use ApiResponse;

    public function __construct(public CounterpartyReportRepositoryInterface $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate(CounterpartySettlementResource::collection($this->repository->index($request->validated())));
    }



}
