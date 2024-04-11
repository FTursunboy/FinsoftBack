<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\OrderStatusResource;
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
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function purchase(DocumentRequest $request): JsonResponse
    {
        return $this->created(DocumentResource::make($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_PURCHASE)));
    }

    public function returnList(IndexRequest $request): JsonResponse
    {
        return $this->success(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function show(Document $document)
    {
        return $this->success(DocumentResource::make($document->load('counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods')));
    }

    public function return(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_RETURN));
    }

    public function approve(Document $document)
    {
        return $this->success($this->repository->approve($document));
    }

    public function order(OrderDocumentRequest $request)
    {
        return $this->created(OrderStatusResource::make($this->repository->order(OrderDocumentDTO::fromRequest($request))));
    }
}
