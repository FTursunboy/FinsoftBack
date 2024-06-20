<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\Api\Good\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\CounterpartyCoordinatesRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\CounterpartyCoordinatesResource;
use App\Http\Resources\GroupResource;
use App\Models\Barcode;
use App\Models\Counterparty;
use App\Models\Good;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\CounterpartyCoordinatesRepository;
use App\Traits\ApiResponse;

class CounterpartyCoordinatesController extends Controller
{
    use ApiResponse;

    public function __construct(public CounterpartyCoordinatesRepository $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate(CounterpartyCoordinatesResource::collection($this->repository->index($request->validated())));
    }


    public function store(CounterpartyCoordinatesRequest $request)
    {

        return $this->created($this->repository->store($request->validated()));
    }
}
