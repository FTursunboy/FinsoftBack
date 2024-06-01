<?php

namespace App\Http\Controllers\Api;

use App\DTO\EmployeeDTO;
use App\DTO\EmployeeUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employee\EmployeeRequest;
use App\Http\Requests\Api\Employee\EmployeeUpdateRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\EmployeeRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(public EmployeeRepositoryInterface $repository)
    {
        $this->authorizeResource(Employee::class, 'employee');
    }

    public function index(IndexRequest $request)
    {
        return $this->paginate(EmployeeResource::collection($this->repository->index($request->validated())));
    }

    public function show(Employee $employee) :JsonResponse
    {
        return $this->success(EmployeeResource::make($employee));
    }

    public function store(EmployeeRepositoryInterface $repository, EmployeeRequest $request)
    {
        return $this->created(EmployeeResource::make($repository->store(EmployeeDTO::fromRequest($request))));
    }

    public function update(Employee $employee, EmployeeUpdateRequest $request, EmployeeRepositoryInterface $repository)
    {
        return $this->success(EmployeeResource::make($repository->update($employee, EmployeeUpdateDTO::fromRequest($request))));
    }

    public function destroy(Employee $employee)
    {
        return $this->deleted($employee->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->deleted($delete->massDelete(new Employee(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Employee(), $request->validated()));
    }

    public function deleteImage(Employee $employee)
    {
        return $this->deleted($this->repository->deleteImage($employee));
    }

    public function export(IndexRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }

}
