<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getUnreadNotifications(array $data) :LengthAwarePaginator;
    public function getAllNotifications(array $data) :LengthAwarePaginator;

    public function read(Notification $notification);

}
