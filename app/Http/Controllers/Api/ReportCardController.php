<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportCard\FilterEmployeeRequest;
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

    public function index()
    {
        $this->authorize('viewAny', ReportCard::class);

        return ReportCardResource::collection(ReportCard::all());
    }

    public function store(ReportCardRequest $request)
    {
        $this->authorize('create', ReportCard::class);

        return new ReportCardResource(ReportCard::create($request->validated()));
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

    public function getEmployees(FilterEmployeeRequest $request) {
        return  $this->paginate($this->repository->getEmployees($request->validated()));
    }
}
