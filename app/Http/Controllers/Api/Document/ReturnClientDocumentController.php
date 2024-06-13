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
use App\Http\Resources\OrderStatusResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\Document\ReturnClientDocumentRepositoryInterface;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReturnClientDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public ReturnClientDocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $indexRequest): JsonResponse
    {
        return $this->paginate(DocumentResource::collection($this->repository->index(Status::CLIENT_RETURN, $indexRequest->validated())));
    }

    public function store(DocumentRequest $request): JsonResponse
    {
        return $this->created(DocumentResource::make($this->repository->store(DocumentDTO::fromRequest($request), Status::CLIENT_RETURN)));
    }

    public function massDelete(IdRequest $request)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Document(), $request->validated()));
    }

    public function statuses()
    {
        return $this->success(OrderStatusResource::collection(OrderStatus::get()));
    }

    public function approve(IdRequest $request)
    {
        $good = $this->repository->approve($request->validated());

        if ($good !== null) {
            return response()->json(['result' => "not enough goods", 'errors' => $good], 400);
        }

        return $this->success($good);
    }
    public function unApprove(IdRequest $request)
    {
        return $this->success($this->repository->unApprove($request->validated()));
    }

}
