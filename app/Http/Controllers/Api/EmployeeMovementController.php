<?php

namespace App\Http\Controllers\Api;

use App\DTO\EmployeeMovementDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmployeeMovement\EmployeeMovementRequest;
use App\Http\Requests\Api\EmployeeMovement\FilterRequest;
use App\Http\Resources\EmployeeMovementResource;
use App\Models\EmployeeMovement;
use App\Repositories\Contracts\EmployeeMovementRepositoryInterface;
use App\Traits\ApiResponse;

class EmployeeMovementController extends Controller
{
    use ApiResponse;

    public function __construct(public EmployeeMovementRepositoryInterface $employeeMovementRepository)
    {
    }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', EmployeeMovement::class);

        return $this->paginate(EmployeeMovementResource::collection($this->employeeMovementRepository->index($request->validated())));
    }

    public function store(EmployeeMovementRequest $request)
    {
        $this->authorize('create', EmployeeMovement::class);

        return $this->created(new EmployeeMovementResource($this->employeeMovementRepository->store(EmployeeMovementDTO::fromRequest($request))));
    }

    public function show(EmployeeMovement $employeeMovement)
    {
        $this->authorize('view', $employeeMovement);

       return EmployeeMovementResource::make($employeeMovement->load(['position', 'employee', 'department', 'organization', 'schedule']));
         }

    public function update(EmployeeMovementRequest $request, EmployeeMovement $employeeMovement)
    {
        $this->authorize('update', $employeeMovement);

        return $this->created(new EmployeeMovementResource($this->employeeMovementRepository->update($employeeMovement, EmployeeMovementDTO::fromRequest($request))));
    }

    public function destroy(EmployeeMovement $employeeMovement)
    {
        $this->authorize('delete', $employeeMovement);

        $employeeMovement->delete();

        return response()->json();
    }
}
