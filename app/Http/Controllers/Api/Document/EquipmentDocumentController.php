<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\EquipmentDocumentDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\Enums\ApiResponse as ApiResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\DocumentUpdateRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\EquipmentDocument\EquipmentDocumentRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\EquipmentDocumentResource;
use App\Http\Resources\Document\OrderDocumentResource;
use App\Http\Resources\OrderStatusResource;
use App\Models\Document;
use App\Models\Equipment;
use App\Models\OrderDocument;
use App\Models\OrderStatus;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\Document\ClientDocumentRepositoryInterface;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\EquipmentDocumentRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EquipmentDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public EquipmentDocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $indexRequest): JsonResponse
    {
        return $this->paginate(EquipmentDocumentResource::collection($this->repository->index( $indexRequest->validated())));
    }
    public function store(EquipmentDocumentRequest $request): JsonResponse
    {
        return $this->created(EquipmentDocumentResource::make($this->repository->store(EquipmentDocumentDTO::fromRequest($request))));
    }

    public function show(Equipment $equipment)
    {
        return $this->success(EquipmentDocumentResource::make($equipment->load('organization', 'storage', 'good', 'documentGoods', 'documentGoods.good', 'author')));
    }

    public function update(Equipment $document, EquipmentDocumentRequest $request): JsonResponse
    {
        return $this->success($this->repository->update($document, EquipmentDocumentDTO::fromRequest($request)));
    }

    public function massDelete(IdRequest $request)
    {
        return $this->success($this->repository->massDelete($request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Document(), $request->validated()));
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
