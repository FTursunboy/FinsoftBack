<?php

namespace App\Repositories;

use App\DTO\ScheduleDTO;
use App\Models\Month;
use App\Models\Schedule;
use App\Models\ScheduleWeekHours;
use App\Models\WorkerSchedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Schedule::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        return $this->getData($filterParams)->paginate($filterParams['itemsPerPage']);
    }

    public function getData(array $filterParams)
    {
        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['workerSchedule.month', 'weekHours']);

        return $this->filter($filterParams, $query);
    }

    public function store(ScheduleDTO $DTO) :Schedule
    {
        $schedule = $this->model::create([
            'name' => $DTO->name,
        ]);

        WorkerSchedule::insert($this->workerSchedule($DTO->data, $schedule));

        ScheduleWeekHours::insert($this->insertWeekHours($DTO->weeks, $schedule));

        return $schedule->load('workerSchedule', 'weekHours');
    }

    public function update(ScheduleDTO $DTO, Schedule $schedule) :Schedule
    {
        $schedule->update([
            'name' => $DTO->name,
        ]);

        $this->updateWeekHours($DTO->weeks);

        $this->updateWorkerSchedule($DTO->data);

        return $schedule;
    }

    public function insertWeekHours(array $weeks, Schedule $schedule) :array
    {
        return array_map(function ($item) use ($weeks, $schedule) {
            return [
                'schedule_id' => $schedule->id,
                'week' => $item['week'],
                'hours' => $item['hour']
            ];
        }, $weeks);
    }

    public function filter(array $data, $query)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
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

    public function updateWeekHours(array $weeks)
    {
        foreach ($weeks as $week) {
            ScheduleWeekHours::updateOrCreate(
                ['id' => $week['id']],
                [
                    'week' => $week['week'],
                    'hours' => $week['hour'],
                ]
            );
        }
    }

    public function updateWorkerSchedule(array $schedules)
    {
        foreach ($schedules as $schedule) {
            WorkerSchedule::updateOrCreate(
                ['id' => $schedule['id']],
                [
                    'month_id' => $schedule['month_id'],
                    'number_of_hours' => $schedule['number_of_hours'],
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }

    public function excel(array $data): string
    {
        $filterParams = $this->processSearchData($data);

        $result = $this->getData($filterParams)->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'ВСК', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);

        foreach ($result as $rows) {
            $array = [];
            $array[] = $rows->name;

            foreach ($rows->weekHours as $row) {
                $array[] = $row->hours;
            }

            $array[] = $rows->deleted_at ? 'Да' : 'Нет';

            $row = WriterEntityFactory::createRowFromArray($array);
            $writer->addRow($row);
        }

        $writer->close();

        return $filePath;
    }

}
