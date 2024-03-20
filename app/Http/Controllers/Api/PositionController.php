<?php

namespace App\Http\Controllers\Api;

use App\DTO\PositionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Position\FilterRequest;
use App\Http\Requests\Api\Position\PositionRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\PositionResource;
use App\Models\Currency;
use App\Models\Position;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Repositories\PositionRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    use ApiResponse;

    public function __construct(public PositionRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request): JsonResponse
    {
        return $this->paginate(PositionResource::collection($this->repository->index($request->validated())));
    }

    public function show(Position $position) :JsonResponse
    {
        return $this->success(PositionResource::make($position));
    }

    public function store(PositionRepository $repository, PositionRequest $request): JsonResponse
    {
        return $this->created(PositionResource::make($repository->store(PositionDTO::formRequest($request))));
    }

    public function update(Position $position, PositionRequest $request, PositionRepository $repository)
    {
        return $this->success(PositionResource::make($repository->update($position, PositionDTO::formRequest($request))));
    }

    public function destroy(Position $position)
    {
        return $this->deleted($position->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->success($delete->massDelete(new Position(), $request->validated()));
    }

    public function restore(Position $position)
    {
        return $this->success($position->restore());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Position(), $request->validated()));
    }
}
