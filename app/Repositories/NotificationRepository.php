<?php

namespace App\Repositories;

use App\DTO\CashRegisterDTO;
use App\Models\CashRegister;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{

    public $model = Notification::class;

    public function getUnreadNotifications(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::query()->where('user_id', \Auth::id())->where('read_at', null);

        return $query->paginate($filterParams['itemsPerPage']);
    }


    public function getAllNotifications(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);


        $query = $this->model::query()->where('user_id', \Auth::id());

        return $query->paginate($filterParams['itemsPerPage']);
    }



    public function read(Notification $notification)
    {
        $notification->update(['read_at' => now()]);
    }
}
