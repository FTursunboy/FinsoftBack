<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeMovementRequest;
use App\Http\Resources\EmployeeMovementResource;
use App\Models\EmployeeMovement;

class EmployeeMovementController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', EmployeeMovement::class);

        return EmployeeMovementResource::collection(EmployeeMovement::all());
    }

    public function store(EmployeeMovementRequest $request)
    {
        $this->authorize('create', EmployeeMovement::class);

        return new EmployeeMovementResource(EmployeeMovement::create($request->validated()));
    }

    public function show(EmployeeMovement $employeeMovement)
    {
        $this->authorize('view', $employeeMovement);

        return new EmployeeMovementResource($employeeMovement);
    }

    public function update(EmployeeMovementRequest $request, EmployeeMovement $employeeMovement)
    {
        $this->authorize('update', $employeeMovement);

        $employeeMovement->update($request->validated());

        return new EmployeeMovementResource($employeeMovement);
    }

    public function destroy(EmployeeMovement $employeeMovement)
    {
        $this->authorize('delete', $employeeMovement);

        $employeeMovement->delete();

        return response()->json();
    }
}
