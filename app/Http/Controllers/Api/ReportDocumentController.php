<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportDocumentRequest;
use App\Http\Resources\BalanceResource;
use App\Http\Resources\CounterpartySettlementResource;
use App\Http\Resources\GoodAccountingResource;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\ReportDocumentRepositoryInterface;
use App\Traits\ApiResponse;

class ReportDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public ReportDocumentRepositoryInterface $repository) { }

    public function getBalance(Documentable $document, ReportDocumentRequest $request)
    {
        return $this->paginate(BalanceResource::collection($this->repository->getBalances($document, $request->validated())));
    }

    public function getCounterpartySettlements(Documentable $document, ReportDocumentRequest $request)
    {
        return $this->paginate(CounterpartySettlementResource::collection($this->repository->getCounterpartySettlements($document, $request->validated())));
    }

    public function getGoodAccountings(Documentable $document, ReportDocumentRequest $request)
    {

        return $this->paginate(GoodAccountingResource::collection($this->repository->getGoodAccountings($document, $request->validated())));
    }
}
