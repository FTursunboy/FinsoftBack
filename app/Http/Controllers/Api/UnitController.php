<?php

namespace App\Http\Controllers\Api;

use App\DTO\UnitDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Unit\FilterRequest;
use App\Http\Requests\Api\Unit\UnitRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\UnitResource;
use App\Models\Currency;
use App\Models\Unit;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Repositories\UnitRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    use ApiResponse;


    public function __construct(public UnitRepositoryInterface $repository)
    {
    }

    public function index(UnitRepository $repository, FilterRequest $request)
    {
        return $this->paginate(UnitResource::collection($repository->index($request->validated())));
    }

    public function store(UnitRequest $request, UnitRepository $repository)
    {
        return $this->created(UnitResource::make($repository->store(UnitDTO::fromRequest($request))));
    }

    public function update(Unit $unit, UnitRequest $request, UnitRepository $repository)
    {
        return $this->success(UnitResource::make($repository->update($unit, UnitDTO::fromRequest($request))));
    }

    public function destroy(Unit $unit)
    {
        return $this->deleted($unit->delete());
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Unit(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Unit(), $request->validated()));
    }
}
