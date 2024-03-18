<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    use ApiResponse;

    public function __construct(public BarcodeRepository $repository)
    {

    }

    public function index(Good $good, IndexRequest $request)
    {
        return $this->success(BarcodeResource::collection($this->repository->index($request->validated(), $good)));
    }

    public function store(BarcodeRequest $request)
    {
        return $this->created(BarcodeResource::make($this->repository->store(BarcodeDTO::fromRequest($request->validated()))));
    }

    public function update(Barcode $barcode, BarcodeRequest $request)
    {
        return $this->success(GroupResource::make($this->repository->update($barcode, BarcodeDTO::fromRequest($request->validated()))));
    }

    public function destroy(Barcode $barcode)
    {
        return $this->deleted($barcode->delete());
    }
}
