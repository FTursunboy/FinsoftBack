<?php

namespace App\Repositories\Contracts;

use App\DTO\WorkerScheduleDTO;
use App\Models\WorkerSchedule;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkerScheduleRepositoryInterface extends IndexInterface
{
    public function store(WorkerScheduleDTO $dto) :WorkerSchedule;

    public function month(array $data) :LengthAwarePaginator;
}
