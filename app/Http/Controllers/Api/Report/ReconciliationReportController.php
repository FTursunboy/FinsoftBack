<?php

namespace App\Http\Controllers\Api\Report;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Report\ReconciliationFilterRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Models\Barcode;
use App\Models\Counterparty;
use App\Models\Good;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\ApiResponse;

class ReconciliationReportController extends Controller
{
    use ApiResponse;

    public function __construct(public ReconciliationReportRepositoryInterface $repository) { }

    public function index(Counterparty $counterparty, ReconciliationFilterRequest $request)
    {
        return $this->success($this->repository->index($counterparty, $request->validated()));
    }

}