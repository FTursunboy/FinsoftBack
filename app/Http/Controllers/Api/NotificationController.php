<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\NotificationResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    public function __construct(public NotificationRepositoryInterface $repository) { }

    public function getUnreadNotifications(IndexRequest $request)
    {
        return  $this->paginate(NotificationResource::collection($this->repository->getUnreadNotifications($request->validated())));
    }

    public function getAllNotifications(IndexRequest $request)
    {
        return  $this->paginate(NotificationResource::collection($this->repository->getAllNotifications($request->validated())));
    }

    public function read(Notification $notification)
    {
        return $this->success($this->repository->read($notification));
    }

    public function exists()
    {
        return $this
    }
}
