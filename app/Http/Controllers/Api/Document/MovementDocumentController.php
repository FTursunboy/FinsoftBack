<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\MovementDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DeleteDocumentGoodRequest;
use App\Http\Requests\Api\MovementDocument\FilterRequest;
use App\Http\Requests\Api\MovementDocument\MovementDocumentRequest;
use App\Http\Resources\Document\MovementDocumentResource;
use App\Models\MovementDocument;
use App\Repositories\Contracts\Document\MovementDocumentRepositoryInterface;
use App\Traits\ApiResponse;

class MovementDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public MovementDocumentRepositoryInterface $service){ }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', MovementDocument::class);

        return $this->paginate(MovementDocumentResource::collection($this->service->index($request->validated())));
    }

    public function store(MovementDocumentRequest $request)
    {
        $this->authorize('create', MovementDocument::class);

        return new MovementDocumentResource($this->service->store(MovementDocumentDTO::fromRequest($request)));
    }

    public function show(MovementDocument $movement)
    {
        $this->authorize('view', $movement);

        return new MovementDocumentResource($movement->load(['sender_storage', 'recipient_storage', 'author', 'organization', 'goods', 'goods.good', 'documentGoodsWithCount']));
    }

    public function update(MovementDocumentRequest $request, MovementDocument $movement)
    {
        $this->authorize('update', $movement);

        return $this->success(new MovementDocumentResource($this->service->update($movement, MovementDocumentDTO::fromRequest($request))));
    }

    public function destroy(MovementDocument $movementDocument)
    {
        $this->authorize('delete', $movementDocument);

        $movementDocument->delete();

        return response()->json();
    }

    public function deleteDocumentGoods(DeleteDocumentGoodRequest $request)
    {
        return $this->deleted($this->service->deleteDocumentGoods(DeleteDocumentGoodsDTO::fromRequest($request)));
    }
}
