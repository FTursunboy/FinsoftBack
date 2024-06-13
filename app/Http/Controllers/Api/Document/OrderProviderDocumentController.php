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
use App\Repositories\Contracts\Document\OrderProviderDocumentRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Google\Service\Firestore\Order;
use Illuminate\Http\JsonResponse;

class OrderProviderDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public OrderProviderDocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate(OrderDocumentResource::collection($this->repository->index($request->validated(), OrderType::PROVIDER)));
    }

    public function store(OrderDocumentRequest $request)
    {
        return $this->created(OrderDocumentResource::make($this->repository->store(OrderDocumentDTO::fromRequest($request), OrderType::PROVIDER)));
    }

    public function show(OrderDocument $orderDocument)
    {
        return $this->success(
            OrderDocumentResource::make(
                $orderDocument->load('counterparty', 'organization', 'author', 'counterpartyAgreement', 'currency', 'orderDocumentGoods')
            )
        );
    }

    public function approve(IdRequest $request)
    {
        return $this->success($this->repository->approve($request->validated()));
    }

    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new OrderDocument(), $request->validated()));
    }

    public function copy(OrderDocument $orderDocument)
    {
        return $this->success(OrderDocumentResource::make($this->repository->copy($orderDocument)));
    }

}
