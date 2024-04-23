<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Unit;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class DepartmentController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $this->authorize('viewAny', Department::class);

        return $this->success(DepartmentResource::collection(Department::all()));
    }

    public function store(DepartmentRequest $request)
    {

        $this->authorize('create', Department::class);

        return new DepartmentResource(Department::create($request->validated()));
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);

        return new DepartmentResource($department);
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);

        $department->update($request->validated());

        return new DepartmentResource($department);
    }

    public function destroy(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Department(), $request->validated());
    }

    public function restore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Department(), $request->validated()));
    }
}
