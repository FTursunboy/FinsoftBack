<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Department::class);

        return DepartmentResource::collection(Department::all());
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

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        $department->delete();

        return response()->json();
    }
}
