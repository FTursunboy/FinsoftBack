<?php

namespace App\Http\Controllers\Api\Report;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Report\FilterRequest;
use App\Http\Resources\Report\CounterpartySettlementResource;
use App\Repositories\Contracts\Report\CounterpartyReportRepositoryInterface;
use App\Repositories\Contracts\Report\OrganizationReportRepository;
use App\Traits\ApiResponse;

class OrganizationReportController extends Controller
{
    use ApiResponse;

    public function __construct(public OrganizationReportRepository $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate($this->repository->index($request->validated()));
    }


    public function export(FilterRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }



}
