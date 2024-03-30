<?php

namespace App\Http\Controllers\Api;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageEmployeeUpdateDTO;
use App\DTO\StorageUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Storage\StorageEmployeeRequest;
use App\Http\Requests\Api\Storage\StorageEmployeeUpdateRequest;
use App\Http\Requests\Api\Storage\StorageRequest;
use App\Http\Requests\Api\User\UserRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\EmployeeStorageResource;
use App\Http\Resources\StorageResource;
use App\Models\EmployeeStorage;
use App\Models\Storage;
use App\Repositories\Contracts\MassDeleteInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\StorageEmployeeRepositoryInterface;
use App\Repositories\Contracts\StorageRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class StorageEmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(public StorageEmployeeRepositoryInterface $repository)
    {
    }

    public function getEmployeesByStorageId(Storage $storage, IndexRequest $indexRequest, StorageEmployeeRepositoryInterface $repository)
    {
        return $this->paginate(EmployeeStorageResource::collection($repository->getEmployeesByStorageId($storage, $indexRequest->validated())));
    }
}
