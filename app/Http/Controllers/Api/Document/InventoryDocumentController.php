<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\InventoryDocumentDTO;
use App\DTO\Document\InventoryDocumentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\InventoryDocument\InventoryDocumentRequest;
use App\Http\Requests\Api\InventoryDocument\InventoryDocumentUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Document\InventoryDocumentResource;
use App\Models\InventoryDocument;
use App\Repositories\Contracts\InventoryDocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InventoryDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public InventoryDocumentRepositoryInterface $repository) { }

    public function index(IndexRequest $indexRequest): JsonResponse
    {
        return $this->paginate(InventoryDocumentResource::collection($this->repository->index($indexRequest->validated())));
    }

    public function store(InventoryDocumentRequest $request): JsonResponse
    {
        return $this->created(InventoryDocumentResource::make($this->repository->store(InventoryDocumentDTO::fromRequest($request))));
    }

    public function show(InventoryDocument $inventoryDocument)
    {
        return $this->success(InventoryDocumentResource::make($inventoryDocument->load('organization', 'storage', 'author', 'responsiblePerson', 'inventoryDocumentGoods')));
    }

    public function update(InventoryDocument $inventoryDocument, InventoryDocumentUpdateRequest $request)
    {
        return $this->success(InventoryDocumentResource::make($this->repository->update($inventoryDocument, InventoryDocumentUpdateDTO::fromRequest($request))));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new InventoryDocument(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new InventoryDocument(), $request->validated()));
    }
}