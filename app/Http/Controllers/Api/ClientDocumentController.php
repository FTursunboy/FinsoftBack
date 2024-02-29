<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ClientDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public DocumentRepositoryInterface $repository) { }

    public function index(IndexRequest $indexRequest): JsonResponse
    {
        return $this->success(DocumentResource::collection($this->repository->index(Status::CLIENT_PURCHASE, $indexRequest->validated())));
    }

    public function purchase(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::CLIENT_PURCHASE));
    }

    public function returnList(IndexRequest $indexRequest): JsonResponse
    {
        return $this->success(DocumentResource::collection($this->repository->index(Status::CLIENT_RETURN, $indexRequest->validated())));
    }

    public function return(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::CLIENT_RETURN));
    }

    public function massDelete(IdRequest $request, MassDeleteInterface $delete)
    {
        return $delete->massDelete(new Document(), $request->validated());
    }
}
