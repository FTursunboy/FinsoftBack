<?php

namespace App\Http\Controllers\Api;

use App\DTO\ReportCardDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportCard\EmployeeSalaryRequest;
use App\Http\Requests\Api\ReportCard\FilterEmployeeRequest;
use App\Http\Requests\Api\ReportCard\FilterRequest;
use App\Http\Requests\Api\ReportCard\ReportCardRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ReportCardResource;
use App\Models\Employee;
use App\Models\Month;
use App\Models\Organization;
use App\Models\ReportCard;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Traits\ApiResponse;

class ReportCardController extends Controller
{

    use ApiResponse;

    public function __construct(public ReportCardRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', ReportCard::class);

        return $this->paginate(ReportCardResource::collection($this->repository->index($request->validated())));
    }

    public function store(ReportCardRequest $request)
    {
        $this->authorize('create', ReportCard::class);

        return new ReportCardResource($this->repository->store(ReportCardDTO::fromRequest($request)));
    }

    public function show(ReportCard $reportCard)
    {
        $this->authorize('view', $reportCard);

        return new ReportCardResource($reportCard);
    }

    public function update(ReportCardRequest $request, ReportCard $reportCard)
    {
        $this->authorize('update', $reportCard);

        $reportCard->update($request->validated());

        return new ReportCardResource($reportCard);
    }

    public function destroy(ReportCard $reportCard)
    {
        $this->authorize('delete', $reportCard);

        $reportCard->delete();

        return response()->json();
    }

    public function getEmployees(FilterEmployeeRequest $request)
    {
        return  $this->success($this->repository->getEmployees($request->validated()));
    }

    public function getEmployeesSalary(EmployeeSalaryRequest $request)
    {
        return $this->repository->getEmployeesSalary($request->validated());
    }
}
