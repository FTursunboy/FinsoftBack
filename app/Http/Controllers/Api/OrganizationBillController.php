<?php

namespace App\Http\Controllers\Api;

use App\DTO\OrganizationBillDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrganizationBill\FilterRequest;
use App\Http\Requests\Api\OrganizationBill\OrganizationBillRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\OrganizationBillResource;
use App\Models\OrganizationBill;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\OrganizationBillRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrganizationBillController extends Controller
{
    use ApiResponse;

    public function __construct(public OrganizationBillRepositoryInterface $repository)
    {

    }

    public function index(FilterRequest $request) :JsonResponse
    {
        return $this->paginate(OrganizationBillResource::collection($this->repository->index($request->validated())));
    }

    public function show(OrganizationBill $organizationBill) :JsonResponse
    {
        return $this->success(OrganizationBillResource::make($organizationBill));
    }

    public function store(OrganizationBillRequest $request) :JsonResponse
    {
        return $this->created($this->repository->store(OrganizationBillDTO::fromRequest($request)));
    }

    public function update(OrganizationBill $organizationBill, OrganizationBillRequest $request) :JsonResponse
    {
        return $this->success(OrganizationBillResource::make($this->repository->update($organizationBill, OrganizationBillDTO::fromRequest($request))));
    }

    public function destroy(OrganizationBill $organizationBill) :JsonResponse
    {
        return $this->deleted($organizationBill->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new OrganizationBill(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new OrganizationBill(), $request->validated()));
    }

    public function export(FilterRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }
}
