<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProviderDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public DocumentRepositoryInterface $repository) { }

    public function index(IndexRequest $request): JsonResponse
    {
        return $this->success(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function purchase(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_PURCHASE));
    }

    public function returnList(IndexRequest $request): JsonResponse
    {
        return $this->success(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function return(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_RETURN));
    }

    public function approve(Document $document)
    {
        return $this->success($this->repository->approve($document));
    }
}
