<?php

namespace App\Http\Controllers\Api;

use App\DTO\HiringDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Hiring\FilterRequest;
use App\Http\Requests\Api\Hiring\HiringRequest;
use App\Http\Resources\HiringResource;
use App\Models\Hiring;
use App\Repositories\Contracts\HiringRepositoryInterface;
use App\Traits\ApiResponse;
use Hamcrest\Core\Is;

class HiringController extends Controller
{
    use ApiResponse;

    public function __construct(public HiringRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        $this->authorize('viewAny', Hiring::class);

        return $this->success(HiringResource::collection($this->repository->index($request->validated())));
    }

    public function store(HiringRequest $request)
    {
        $this->authorize('create', Hiring::class);

        return $this->created(new HiringResource($this->repository->store(HiringDTO::fromRequest($request))));
    }

    public function show(Hiring $hiring)
    {
        $this->authorize('view', $hiring);

        return new HiringResource($hiring->load('department', 'employee', 'position', 'organization'));
    }

    public function update(HiringRequest $request, Hiring $hiring)
    {
        $this->authorize('update', $hiring);

        return $this->success($this->repository->update($hiring, HiringDTO::fromRequest($request)));
    }

    public function destroy(Hiring $hiring)
    {
        $this->authorize('delete', $hiring);

        $hiring->delete();

        return response()->json();
    }
}
