<?php

namespace App\Http\Controllers\Api\InventoryOperation;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\InventoryOperationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\ReportDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\InventoryOperationRequest;
use App\Http\Resources\BalanceResource;
use App\Http\Resources\CounterpartySettlementResource;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\GoodAccountingResource;
use App\Http\Resources\InventoryOperationResource;
use App\Models\Document;
use App\Models\InventoryOperation;
use App\Models\Status;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\InventoryOperationRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\ReportDocumentRepositoryInterface;
use App\Repositories\Document\InventoryOperationRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InventoryOperationController extends Controller
{
    use ApiResponse;

    public function __construct(public InventoryOperationRepositoryInterface $repository) { }

    public function index(FilterRequest $request, string $type): JsonResponse
    {
        return $this->paginate(InventoryOperationResource::collection($this->repository->index($type, $request->validated())));
    }

    public function store(InventoryOperationRequest $request): JsonResponse
    {
        return $this->created(InventoryOperationResource::make($this->repository->store(InventoryOperationDTO::fromRequest($request))));
    }

    public function update(InventoryOperationRequest $request, InventoryOperation $document): JsonResponse
    {
        return $this->success($this->repository->update($document, InventoryOperationDTO::fromRequest($request)));
    }

    public function show(InventoryOperation $document)
    {
        return $this->success(InventoryOperationResource::make($document->load(['organization', 'storage', 'author',  'currency', 'documentGoods.good'])));
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $repository): JsonResponse
    {
        return $this->success($repository->massDelete(new InventoryOperation(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $repository)
    {
        return $this->success($repository->massRestore(new InventoryOperation(), $request->validated()));
    }



}
