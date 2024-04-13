<?php

namespace App\Http\Controllers;

use App\DTO\MovementDocumentDTO;
use App\Http\Requests\Api\MovementDocument\FilterRequest;
use App\Http\Requests\MovementDocumentRequest;
use App\Http\Resources\MovementDocumentResource;
use App\Models\MovementDocument;
use App\Repositories\Contracts\MovementDocumentRepositoryInterface;
use App\Traits\ApiResponse;

class MovementDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public MovementDocumentRepositoryInterface $service){ }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', MovementDocument::class);

        return MovementDocumentResource::collection($this->service->index($request->validated()));
    }

    public function store(MovementDocumentRequest $request)
    {
        $this->authorize('create', MovementDocument::class);

        return new MovementDocumentResource($this->service->store(MovementDocumentDTO::fromRequest($request->validated())));
    }

    public function show(MovementDocument $movementDocument)
    {
        $this->authorize('view', $movementDocument);

        return new MovementDocumentResource($movementDocument->load(['senderStorage', 'recipientStorage', 'author', 'organization']));
    }

    public function update(MovementDocumentRequest $request, MovementDocument $movementDocument)
    {
        $this->authorize('update', $movementDocument);

        return new MovementDocumentResource($this->success($this->service->update($movementDocument, MovementDocumentDTO::fromRequest($request->validated()))));
    }

    public function destroy(MovementDocument $movementDocument)
    {
        $this->authorize('delete', $movementDocument);

        $movementDocument->delete();

        return response()->json();
    }
}
