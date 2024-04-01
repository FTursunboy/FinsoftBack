<?php

namespace App\Http\Controllers\Api;

use App\DTO\CounterpartyAgreementDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CounterpartyAgreement\CounterpartyAgreementRequest;
use App\Http\Requests\Api\CounterpartyAgreement\CounterpartyAgreementUpdateRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CounterpartyAgreementResource;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Repositories\Contracts\CounterpartyAgreementRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\CounterpartyAgreementRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CounterpartyAgreementController extends Controller
{
    use ApiResponse;

    public function __construct(public CounterpartyAgreementRepositoryInterface $repository)
    {
    }

    public function index(IndexRequest $request) :JsonResponse
    {
        return $this->paginate(CounterpartyAgreementResource::collection($this->repository->index($request->validated())));
    }

    public function show(CounterpartyAgreement $cpAgreement) :JsonResponse
    {
        return $this->success(CounterpartyAgreementResource::make($cpAgreement->load('organization', 'counterparty', 'currency', 'payment', 'priceType')));
    }

    public function store(CounterpartyAgreementRequest $request) :JsonResponse
    {
        return $this->created($this->repository->store(CounterpartyAgreementDTO::fromRequest($request)));
    }

    public function update(CounterpartyAgreement $cpAgreement, CounterpartyAgreementRequest $request) :JsonResponse
    {
        return $this->success(CounterpartyAgreementResource::make($this->repository->update($cpAgreement, CounterpartyAgreementDTO::fromRequest($request))));
    }

    public function getAgreementByCounterpartyId(IndexRequest $request,Counterparty $counterparty): JsonResponse
    {
        return $this->paginate(CounterpartyAgreementResource::collection($this->repository->getAgreementByCounterpartyId($counterparty, $request->validated())));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new CounterpartyAgreement(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new CounterpartyAgreement(), $request->validated()));
    }
}
