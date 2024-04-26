<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportCardRequest;
use App\Http\Resources\ReportCardResource;
use App\Models\ReportCard;

class ReportCardController extends Controller
{
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
}
