<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DocumentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentHistoryResource;
use App\Models\Document;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\Document\ReturnDocumentRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReturnDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public ReturnDocumentRepositoryInterface $repository) { }

    public function update(Document $document, DocumentUpdateRequest $request): JsonResponse
    {
        return $this->success($this->repository->update($document, DocumentUpdateDTO::fromRequest($request)));
    }

    public function changeHistory(Documentable $document)
    {
        return $this->success(DocumentHistoryResource::make($this->repository->changeHistory($document)));
    }

    public function approve(IdRequest $request)
    {
        $good = $this->repository->approve($request->validated());

        if ($good !== null) {
            return $this->error($good, trans('errors.not enough goods'));
        }

        return $this->success($good);
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
