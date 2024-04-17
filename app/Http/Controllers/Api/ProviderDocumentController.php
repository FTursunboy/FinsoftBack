<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\OrderDocumentResource;
use App\Http\Resources\OrderStatusResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProviderDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public DocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $request): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function purchase(DocumentRequest $request): JsonResponse
    {
        return $this->created(DocumentResource::make($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_PURCHASE)));
    }

    public function returnList(IndexRequest $request): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::PROVIDER_PURCHASE, $request->validated())));
    }

    public function show(Document $document)
    {
        return $this->success(DocumentResource::make($document->load('counterparty', 'organization', 'storage', 'author', 'counterpartyAgreement', 'currency', 'documentGoods', 'documentGoods.good')));
    }

    public function return(DocumentRequest $request): JsonResponse
    {
        return $this->created($this->repository->store(DocumentDTO::fromRequest($request), Status::PROVIDER_RETURN));
    }

    public function approve(Document $document)
    {
        return $this->success($this->repository->approve($document));
    }

    public function orderList(FilterRequest $request)
    {

    }

    public function order(OrderDocumentRequest $request)
    {
        return $this->created(OrderDocumentResource::make($this->repository->order(OrderDocumentDTO::fromRequest($request), OrderType::PROVIDER)));
    }

    public function showOrder(OrderDocument $document)
    {
        return $this->success(OrderDocumentResource::make($document->load('counterparty', 'organization', 'author', 'counterpartyAgreement', 'currency', 'orderDocumentGoods')));
    }


}
