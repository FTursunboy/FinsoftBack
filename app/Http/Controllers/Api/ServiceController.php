<?php

namespace App\Http\Controllers\Api;

use App\DTO\Document\ServiceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceRequest;
use App\Repositories\Contracts\Document\ServiceRepositoryInterface;
use App\Traits\ApiResponse;

class ServiceController extends Controller
{
    use ApiResponse;
    public function __construct(public ServiceRepositoryInterface $repository)
    {
    }

    public function store(ServiceRequest $request)
    {
        return $this->success($this->repository->store(ServiceDTO::fromRequest($request)));
    }
}
