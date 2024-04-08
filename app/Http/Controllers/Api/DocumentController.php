<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentHistoryResource;
use App\Models\Document;
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
}
