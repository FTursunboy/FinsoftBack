<?php

namespace App\Http\Controllers\Api;

use App\DTO\Document\ServiceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Repositories\Contracts\Document\ServiceRepositoryInterface;
use App\Traits\ApiResponse;
use function PHPUnit\Framework\isInstanceOf;

class ServiceController extends Controller
{
    use ApiResponse;
    public function __construct(public ServiceRepositoryInterface $repository)
    {
    }

    public function store(ServiceRequest $request)
    {

        $response = $this->repository->store(ServiceDTO::fromRequest($request));

        if (is_array($response) != null) {
            return $this->error("not enough goods", $response);
        }

        return $this->success(ServiceResource::make($response));

    }
}
