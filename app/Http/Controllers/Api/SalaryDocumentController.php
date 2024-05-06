<?php

namespace App\Http\Controllers\Api;

use App\DTO\Document\SalaryDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SalaryDocument\SalaryDocumentRequest;
use App\Http\Resources\SalaryDocumentResource;
use App\Models\SalaryDocument;
use App\Repositories\Contracts\SalaryDocumentRepositoryInterface;
use App\Traits\ApiResponse;

class SalaryDocumentController extends Controller
{
    use ApiResponse;
    public function __construct(public SalaryDocumentRepositoryInterface $repository)
    {

    }

    public function index()
    {
        $this->authorize('viewAny', SalaryDocument::class);

        return SalaryDocumentResource::collection(SalaryDocument::all());
    }

    public function store(SalaryDocumentRequest $request)
    {
        $this->authorize('create', SalaryDocument::class);

        return $this->created($this->repository->store(SalaryDocumentDTO::fromRequest($request)));
    }

    public function show(SalaryDocument $salaryDocument)
    {
        $this->authorize('view', $salaryDocument);

        return new SalaryDocumentResource($salaryDocument);
    }

    public function update(SalaryDocumentRequest $request, SalaryDocument $salaryDocument)
    {
        $this->authorize('update', $salaryDocument);

        $salaryDocument->update($request->validated());

        return new SalaryDocumentResource($salaryDocument);
    }

    public function destroy(SalaryDocument $salaryDocument)
    {
        $this->authorize('delete', $salaryDocument);

        $salaryDocument->delete();

        return response()->json();
    }
}
