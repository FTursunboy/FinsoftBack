<?php

namespace App\Http\Controllers\Api;

use App\DTO\EmployeeMovementDTO;
use App\DTO\FiringDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmployeeMovement\EmployeeMovementRequest;
use App\Http\Requests\Api\EmployeeMovement\FilterRequest;
use App\Http\Requests\Api\Firing\FiringRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\EmployeeMovementResource;
use App\Http\Resources\FiringResource;
use App\Models\EmployeeMovement;
use App\Models\Firing;
use App\Repositories\Contracts\FiringRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class FiringController extends Controller
{
    use ApiResponse;

    public function __construct(public FiringRepositoryInterface $employeeMovementRepository) { }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', EmployeeMovement::class);

        return $this->paginate(EmployeeMovementResource::collection($this->employeeMovementRepository->index($request->validated())));
    }

    public function store(FiringRequest $request)
    {
        $this->authorize('create', EmployeeMovement::class);

        return $this->created(new EmployeeMovementResource($this->employeeMovementRepository->store(FiringDTO::fromRequest($request))));
    }

    public function show(Firing $firing)
    {
        $this->authorize('view', $firing);

        return FiringResource::make($firing->load(['employee', 'organization', 'author']));
    }

    public function update(FiringRequest $request, Firing $firing)
    {
        $this->authorize('update', $firing);

        return $this->success(new EmployeeMovementResource($this->employeeMovementRepository->update($firing, FiringDTO::fromRequest($request))));
    }

    public function destroy(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Firing(), $request->validated());
    }

    public function restore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Firing(), $request->validated()));
    }
}
