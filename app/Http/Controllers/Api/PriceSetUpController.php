<?php

namespace App\Http\Controllers\Api;

use App\DTO\PriceSetUpDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PriceType\FilterRequest;
use App\Http\Requests\PriceSetUpRequest;
use App\Http\Resources\PriceSetUpResource;
use App\Models\PriceSetUp;
use App\Models\PriceType;
use App\Repositories\Contracts\PriceSetUpRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PriceSetUpController extends Controller
{
    use ApiResponse;

    public function __construct(public PriceSetUpRepositoryInterface $repository)
    {
        $this->authorizeResource(PriceType::class, 'priceType');
    }

    public function index(FilterRequest $request) :JsonResponse
    {
        return $this->paginate(PriceSetUpResource::collection($this->repository->index($request->validated())));
    }

    public function store(PriceSetUpRequest $request) :JsonResponse
    {
        return $this->created($this->repository->store(PriceSetUpDTO::fromRequest($request)));
    }

    public function update(PriceSetUp $priceSetUp, PriceSetUpRequest $request) :JsonResponse
    {
        return $this->success(PriceSetUpResource::make($this->repository->update($priceSetUp, PriceSetUpDTO::fromRequest($request))));
    }
}
