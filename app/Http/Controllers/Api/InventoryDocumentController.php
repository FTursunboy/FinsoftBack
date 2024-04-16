<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\DTO\InventoryDocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\InventoryDocument\InventoryDocumentRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\InventoryDocumentResource;
use App\Http\Resources\OrderDocumentResource;
use App\Models\Document;
use App\Models\InventoryDocument;
use App\Models\OrderDocument;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
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

    public function show(InventoryDocument $document)
    {
        return $this->success(InventoryDocumentResource::make($document->load('organization', 'storage', 'author', 'responsiblePerson', 'inventoryDocumentGoods')));
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
