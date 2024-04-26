<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportCardRequest;
use App\Http\Resources\ReportCardResource;
use App\Models\Month;
use App\Models\Organization;
use App\Models\ReportCard;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Repositories\ReportCardRepository;

class ReportCardController extends Controller
{

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

    public function getEmployees(Organization $organization, Month $month) {
        return $this->repository->getEmployees($organization, $month);
    }
}
