<?php

namespace App\Http\Controllers\Api;

use App\DTO\OrganizationDTO;
use App\DTO\OrganizationUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Organization\FilterRequest;
use App\Http\Requests\Api\Organization\OrganizationRequest;
use App\Http\Requests\Api\Organization\OrganizationUpdateRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\OrganizationRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{
    use ApiResponse;

    public function __construct(public OrganizationRepositoryInterface $repository)
    {

    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(OrganizationResource::collection($this->repository->index($request->validated())));
    }

    public function show(Organization $organization) :JsonResponse
    {
        return $this->success(OrganizationResource::make($organization));
    }

    public function store(OrganizationRequest $request, OrganizationRepository $repository)
    {
        return $this->created(OrganizationResource::make($repository->store(OrganizationDTO::fromRequest($request))));
    }

    public function update(Organization $organization, OrganizationUpdateRequest $request, OrganizationRepository $repository)
    {
        return $this->success(OrganizationResource::make($repository->update($organization, OrganizationUpdateDTO::fromRequest($request))));
    }

    public function destroy(Organization $organization)
    {
        return $this->deleted($organization->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Organization(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Organization(), $request->validated()));
    }

    public function export(FilterRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }
}
