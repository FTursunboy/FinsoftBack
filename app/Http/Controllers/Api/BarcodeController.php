<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Repositories\BarcodeRepository;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarcodeController extends Controller
{
    use ApiResponse;

    public function __construct(public BarcodeRepositoryInterface $repository) { }

    public function index(Good $good, IndexRequest $request)
    {
        return $this->paginate(BarcodeResource::collection($this->repository->index($good, $request->validated())));
    }

    public function show(Barcode $barcode)
    {
        return $this->success(BarcodeResource::make($barcode));
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

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $this->deleted($delete->massDelete(new Barcode(), $request->validated()));
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Barcode(), $request->validated()));
    }
}
