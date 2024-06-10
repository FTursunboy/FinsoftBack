<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\OrderDocumentResource;
use App\Http\Resources\OrderStatusResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\OrderClientDocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrderClientDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public OrderClientDocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $request): JsonResponse
    {
        return $this->paginate(OrderDocumentResource::collection($this->repository->index($request->validated(), OrderType::CLIENT)));
    }

    public function store(OrderDocumentRequest $request)
    {
        return $this->created(OrderDocumentResource::make($this->repository->store(OrderDocumentDTO::fromRequest($request), OrderType::CLIENT)));
    }

    public function show(OrderDocument $orderDocument)
    {
        return $this->success(OrderDocumentResource::make($orderDocument->load('counterparty', 'organization', 'author', 'counterpartyAgreement', 'currency', 'orderDocumentGoods', 'orderStatus', 'documentGoodsWithCount')));
    }

    public function updateOrder(OrderDocument $orderDocument, OrderDocumentUpdateRequest $request): JsonResponse
    {
        return $this->success(OrderDocumentResource::make($this->repository->updateOrder($orderDocument, OrderDocumentUpdateDTO::fromRequest($request))));
    }

    public function statuses()
    {
        return $this->success(OrderStatusResource::collection(OrderStatus::get()));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($delete->massDelete(new OrderDocument(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new OrderDocument(), $request->validated()));
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

}
