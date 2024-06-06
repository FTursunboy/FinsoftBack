<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\OrderDocumentResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\ReturnProviderDocumentRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReturnProviderDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public ReturnProviderDocumentRepositoryInterface $repository) { }

    public function index(IndexRequest $request): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::PROVIDER_RETURN, $request->validated())));
    }

    public function store(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_RETURN));
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

}

