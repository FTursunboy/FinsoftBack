<?php

namespace App\Http\Controllers\Api;

use App\DTO\EmployeeMovementDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmployeeMovement\EmployeeMovementRequest;
use App\Http\Requests\Api\EmployeeMovement\FilterRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\EmployeeMovementResource;
use App\Models\EmployeeMovement;
use App\Models\Hiring;
use App\Repositories\Contracts\EmployeeMovementRepositoryInterface;
use App\Repositories\Contracts\repositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class EmployeeMovementController extends Controller
{
    use ApiResponse;

    public function __construct(public EmployeeMovementRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', EmployeeMovement::class);

        return $this->paginate(EmployeeMovementResource::collection($this->repository->index($request->validated())));
    }

    public function store(EmployeeMovementRequest $request)
    {
        $this->authorize('create', EmployeeMovement::class);

        return $this->created(new EmployeeMovementResource($this->repository->store(EmployeeMovementDTO::fromRequest($request))));
    }

    public function show(EmployeeMovement $employeeMovement)
    {
        $this->authorize('view', $employeeMovement);

       return EmployeeMovementResource::make($employeeMovement->load(['position', 'employee', 'department', 'organization', 'schedule']));
         }

    public function update(EmployeeMovementRequest $request, EmployeeMovement $employeeMovement)
    {
        $this->authorize('update', $employeeMovement);

        return $this->created(new EmployeeMovementResource($this->repository->update($employeeMovement, EmployeeMovementDTO::fromRequest($request))));
    }


    public function massDelete(IdRequest $request, MassOperationInterface $repository)
    {
        return $this->success($repository->massDelete(new EmployeeMovement(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $repository)
    {
        return $this->success($repository->massRestore(new EmployeeMovement(), $request->validated()));
    }
}
