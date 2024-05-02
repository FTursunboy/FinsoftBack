<?php

namespace App\Http\Controllers\Api;

use App\DTO\ScheduleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Schedule\CalculateHoursRequest;
use App\Http\Requests\Api\Schedule\ScheduleRequest;
use App\Http\Resources\MonthResource;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Traits\ApiResponse;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function __construct(public ScheduleRepositoryInterface $repository) { }

    public function index(IndexRequest $request)
    {
        return $this->paginate(ScheduleResource::collection($this->repository->index($request->validated())));
    }

    public function store(ScheduleRequest $request)
    {
        return $this->created(ScheduleResource::make($this->repository->store(ScheduleDTO::fromRequest($request))));
    }

    public function show(Schedule $schedule)
    {
        return $this->success(ScheduleResource::make($schedule->load('workerSchedule.month', 'weekHours')));
    }

    public function update(Schedule $schedule, ScheduleRequest $request)
    {
        return $this->success(ScheduleResource::make($this->repository->update(ScheduleDTO::fromRequest($request), $schedule)));
    }

    public function months(IndexRequest $request)
    {
        return $this->paginate(MonthResource::collection($this->repository->month($request->validated())));
    }

    public function calculateHours(CalculateHoursRequest $request)
    {
        return $this->success($this->repository->calculateHours($request->validated()));
    }

}
