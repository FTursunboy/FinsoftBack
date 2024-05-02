<?php

namespace App\Http\Controllers\Api;

use App\DTO\ImageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Image\FilterRequest;
use App\Http\Requests\Api\Image\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Good;
use App\Models\GoodImages;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Traits\ApiResponse;

class ImageController extends Controller
{
    use ApiResponse;

    public function __construct(public ImageRepositoryInterface $repository) { }

    public function index(Good $good, FilterRequest $request)
    {
        return $this->paginate(ImageResource::collection($this->repository->index($good, $request->validated())));
    }

    public function store(ImageRequest $request)
    {
        return $this->created(ImageResource::make($this->repository->store(ImageDTO::fromRequest($request))));
    }

    public function destroy(GoodImages $images)
    {
        return $this->deleted($this->repository->delete($images));
    }
}
