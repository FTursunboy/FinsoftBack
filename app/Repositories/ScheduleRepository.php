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

        $query = $this->sort($filterParams, $query, ['workerSchedule.month', 'weekHours']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(ScheduleDTO $DTO) :Schedule
    {
        $schedule = $this->model::create([
            'name' => $DTO->name,
        ]);

        WorkerSchedule::insert($this->workerSchedule($DTO->data, $schedule));

        $this->insertWeekHours($DTO->weeks, $schedule);

        return $schedule->load('workerSchedule', 'weekHours');
    }

    public function update(ScheduleDTO $DTO, Schedule $schedule) :Schedule
    {
        $schedule->update([
            'name' => $DTO->name,
        ]);

        WorkerSchedule::updateOrInsert($this->workerSchedule($DTO->weeks, $schedule));

        return $schedule;
    }

    public function insertWeekHours(array $weeks, Schedule $schedule) :void {
        $array_to_insert = array_map(function ($item) use ($weeks, $schedule) {
            return [
                'schedule_id' => $schedule->id,
                'week' => $item['week'],
                'hours' => $item['hour']
            ];
        }, $weeks);

        \DB::table('schedule_week_hours')->insert($array_to_insert);
    }

    public function workerSchedule(array $data, Schedule $schedule) :array
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

    public function month(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = Month::query();

        return $query->paginate($filterParams['itemsPerPage']);
    }



    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('name', 'like', '%' . implode('%', $searchTerm) . '%');
    }

    public function calculateHours(array $weeks) :array
    {
        $currentYear = date('Y');
        $totalHoursByMonth = [];


        for ($month = 1; $month <= 12; $month++) {

            $monthName = Month::find($month)->name;

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);

            $daysCount = [
                'Monday'    => 0,
                'Tuesday'   => 0,
                'Wednesday' => 0,
                'Thursday'  => 0,
                'Friday'    => 0,
                'Saturday'  => 0,
                'Sunday'    => 0,
            ];


            for ($day = 1; $day <= $daysInMonth; $day++) {
                $weekDay = date('l', strtotime("$currentYear-$month-$day"));

                $daysCount[$weekDay]++;
            }


            $totalHours = 0;

            foreach ($weeks['weeks'] as $week) {
                $weekName = array_keys($daysCount)[$week['week']];
                $totalHours += $daysCount[$weekName] * $week['hour'];
            }



            $totalHoursByMonth[] = [
                'month' => $monthName,
                'hours' => $totalHours,

            ];
        }

        return $totalHoursByMonth;
    }

}
