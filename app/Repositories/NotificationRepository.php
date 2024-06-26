<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
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

    public function read(array $data)
    {
        foreach ($data['ids'] as $id) {
            Notification::find($id)->update(['read_at' => now()]);
        }
    }
}
