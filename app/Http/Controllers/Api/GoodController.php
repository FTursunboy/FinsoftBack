<?php

namespace App\Http\Controllers\Api;

use App\DTO\GoodDTO;
use App\DTO\GoodUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Good\CountGoodsRequest;
use App\Http\Requests\Api\Good\FilterRequest;
use App\Http\Requests\Api\Good\GoodRequest;
use App\Http\Requests\Api\Good\GoodUpdateRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\GoodHistoryResource;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodWithImagesResource;
use App\Models\Good;
use App\Models\Storage;
use App\Repositories\Contracts\GoodRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\GoodRepository;
use App\Traits\ApiResponse;

class GoodController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodRepositoryInterface $repository)
    {

    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(GoodResource::collection($this->repository->index($request->validated())));
    }

    public function store(GoodRequest $request, GoodRepositoryInterface $repository)
    {
        return $this->created(GoodResource::make($repository->store(GoodDTO::fromRequest($request))));
    }

    public function show(Good $good)
    {
        return $this->success(GoodWithImagesResource::make($good->load(['category', 'unit', 'images', 'storage', 'goodGroup', 'location'])));
    }

    public function update(Good $good, GoodUpdateRequest $request)
    {
        return $this->success(GoodResource::make($this->repository->update($good, GoodUpdateDTO::fromRequest($request))));
    }

    public function getByBarcode(string $barcode)
    {
        $good = $this->repository->getByBarcode($barcode);
        if (!$good) {
            return $this->notFound();
        }
        return $this->success(GoodResource::make($good));
    }

    public function massDelete(IdRequest $request, MassOperationInterface $delete)
    {
        return $delete->massDelete(new Good(), $request->validated());
    }

    public function massRestore(IdRequest $request, MassOperationInterface $restore)
    {
        return $this->success($restore->massRestore(new Good(), $request->validated()));
    }

    public function history(Good $good)
    {
        return $this->success(GoodHistoryResource::collection($this->repository->history($good)));
    }

    public function export(FilterRequest $request)
    {
        return response()->download($this->repository->export($request->validated()))->deleteFileAfterSend();
    }

    public function countGoods(CountGoodsRequest $request)
    {
        return $this->paginate(GoodResource::collection($this->repository->countGoods($request->validated())));
    }

    public function countGoodsByGoodId(CountGoodsRequest $request)
    {
        return $this->success(GoodResource::make($this->repository->countGoodsByGoodId($request->validated())));
    }
}
