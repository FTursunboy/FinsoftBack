<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentUpdateDTO;
use App\DTO\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentHistoryResource;
use App\Http\Resources\UserResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
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

    public function updateOrder(OrderDocument $document, OrderDocumentRequest $request): JsonResponse
    {
        return $this->success($this->repository->updateOrder($document, OrderDocumentDTO::fromRequest($request)));
    }

    public function changeHistory(Document $document)
    {
        return $this->success(DocumentHistoryResource::make($this->repository->changeHistory($document)));
    }

    public function approve(Document $document)
    {
        return $this->success($this->repository->approve($document));
    }

    public function unApprove(Document $document)
    {
        return $this->success($this->repository->unApprove($document));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Document(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Document(), $request->validated()));
    }

    public function documentAuthor()
    {
        return $this->success(UserResource::collection($this->repository->documentAuthor()));
    }
}
