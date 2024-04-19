<?php

namespace App\Http\Controllers\Api;

use App\DTO\DocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\OrderDocumentResource;
use App\Http\Resources\OrderStatusResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ClientDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public DocumentRepositoryInterface $repository) { }

    public function index(IndexRequest $indexRequest): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::CLIENT_PURCHASE, $indexRequest->validated())));
    }

    public function purchase(DocumentRequest $request): JsonResponse
    {
        return $this->created(DocumentResource::make($this->repository->store(DocumentDTO::fromRequest($request), Status::CLIENT_PURCHASE)));
    }

    public function returnList(IndexRequest $indexRequest): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::CLIENT_RETURN, $indexRequest->validated())));
    }

    public function return(DocumentRequest $request): JsonResponse
    {
        return $this->created(DocumentResource::make($this->repository->store(DocumentDTO::fromRequest($request), Status::CLIENT_RETURN)));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Document(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Document(), $request->validated()));
    }

    public function orderList(FilterRequest $request): JsonResponse
    {
        return $this->paginate(OrderDocumentResource::collection($this->repository->orderList($request->validated(), OrderType::CLIENT)));
    }

    public function order(OrderDocumentRequest $request)
    {
        return $this->created(OrderDocumentResource::make($this->repository->order(OrderDocumentDTO::fromRequest($request), OrderType::CLIENT)));
    }

    public function showOrder(OrderDocument $orderDocument)
    {
        return $this->success(OrderDocumentResource::make($orderDocument->load('counterparty', 'organization', 'author', 'counterpartyAgreement', 'currency', 'orderDocumentGoods', 'orderStatus')));
    }

    public function statuses()
    {
        return $this->success(OrderStatusResource::collection(OrderStatus::get()));
    }
}
