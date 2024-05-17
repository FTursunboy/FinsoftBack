<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DeleteDocumentGoodRequest;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\OrderDocumentResource;
use App\Http\Resources\DocumentHistoryResource;
use App\Models\Document;
use App\Models\DocumentModel;
use App\Models\OrderDocument;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public DocumentRepositoryInterface $repository) { }

    public function update(Document $document, DocumentUpdateRequest $request): JsonResponse
    {
        return $this->success($this->repository->update($document, DocumentUpdateDTO::fromRequest($request)));
    }

    public function updateOrder(OrderDocument $orderDocument, OrderDocumentUpdateRequest $request): JsonResponse
    {
        return $this->success(OrderDocumentResource::make($this->repository->updateOrder($orderDocument, OrderDocumentUpdateDTO::fromRequest($request))));
    }

    public function changeHistory(Documentable $document)
    {
        return $this->success(DocumentHistoryResource::make($this->repository->changeHistory($document)));
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Document(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Document(), $request->validated()));
    }

    public function deleteDocumentGoods(DeleteDocumentGoodRequest $request)
    {
        return $this->deleted($this->repository->deleteDocumentGoods(DeleteDocumentGoodsDTO::fromRequest($request)));
    }

    public function copy(Document $document)
    {
        return $this->success(DocumentResource::make($this->repository->copy($document)));
    }

}
