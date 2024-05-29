<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\DTO\LocationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Location\FilterRequest;
use App\Http\Requests\Api\Location\LocationRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\LocationResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Location;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;

class LocationController extends Controller
{
    use ApiResponse;

    public function __construct(public LocationRepositoryInterface $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate(LocationResource::collection($this->repository->index($request->validated())));
    }

    public function show(Location $location)
    {
        return $this->success(LocationResource::make($location));
    }

    public function store(LocationRequest $request)
    {
        return $this->created(LocationResource::make($this->repository->store(LocationDTO::fromRequest($request))));
    }

    public function update(Location $location, LocationRequest $request)
    {
        return $this->success(LocationResource::make($this->repository->update($location, LocationDTO::fromRequest($request))));
    }

    public function destroy(Location $location)
    {
        return $this->deleted($location->delete());
    }
}
