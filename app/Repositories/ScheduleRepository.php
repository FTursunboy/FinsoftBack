<?php

namespace App\Repositories;

use App\DTO\ScheduleDTO;
use App\Models\Month;
use App\Models\Schedule;
use App\Models\WorkerSchedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Schedule::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['workerSchedule']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(ScheduleDTO $DTO) :Schedule
    {
        $schedule = $this->model::create([
            'name' => $DTO->name,
        ]);

        WorkerSchedule::insert($this->workerSchedule($DTO->data, $schedule));

        return $schedule;
    }

    public function month(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Month::query();

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function workerSchedule(array $data, Schedule $schedule)
    {
        return array_map(function ($item) use ($schedule) {
            return [
                'schedule_id' => $schedule->id,
                'month_id' => $item['month_id'],
                'number_of_hours' => $item['number_of_hours'],
                'created_at' => Carbon::now()
            ];
        }, $data);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('name', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
