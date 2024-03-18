<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return $this->created(BarcodeResource::make($this->repository->store(BarcodeDTO::fromRequest($request))));
    }

    public function update(Barcode $barcode, BarcodeRequest $request)
    {
        return $this->success(GroupResource::make($this->repository->update($barcode, BarcodeDTO::fromRequest($request))));
    }

    public function destroy(Barcode $barcode)
    {
        return $this->deleted($barcode->delete());
    }
}
