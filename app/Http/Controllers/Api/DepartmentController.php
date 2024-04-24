<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Department\DepartmentRequest;
use App\Http\Requests\Api\Department\FilterRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Unit;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', Department::class);

        $data = $request->validated();

        $query = Department::filter(Department::filterData($data));

        return $this->paginate(DepartmentResource::collection($query->paginate($data['itemsPerPage'])));
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
