<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\OperationRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\ItemResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Resource;
use App\Models\User;
use App\Repositories\BarcodeRepository;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class PermissionController extends Controller
{
    use ApiResponse;

    public function __construct(public PermissionRepositoryInterface $repository)
    {

    }

    public function givePermission(User $user, OperationRequest $request) :JsonResponse
    {
        return $this->success($this->repository->givePermissions($user, $request->validated()));
    }

    public function getPermissions(User $user) :JsonResponse
    {
        return $this->success($this->repository->getPermissions($user));
    }

    public function getResources()
    {
        return $this->paginate(ItemResource::collection(Resource::paginate(20)));
    }
}
