<?php

namespace App\Repositories\Contracts;

use App\DTO\WorkerScheduleDTO;
use App\Models\WorkerSchedule;

interface WorkerScheduleRepositoryInterface extends IndexInterface
{
    public function store(WorkerScheduleDTO $dto) :WorkerSchedule;
}
