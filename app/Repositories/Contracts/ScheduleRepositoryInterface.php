<?php

namespace App\Repositories\Contracts;

use App\DTO\ScheduleDTO;
use App\Models\Schedule;
use App\Models\WorkerSchedule;
use Illuminate\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface extends IndexInterface
{
    public function store(ScheduleDTO $dto) :Schedule;

    public function update(ScheduleDTO $DTO, Schedule $schedule) :Schedule;

    public function month(array $data) :LengthAwarePaginator;

    public function calculateHours(array $weeks) :array;
}
