<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\Good;
use App\Models\Hiring;
use App\Models\Month;
use App\Models\Organization;
use App\Models\ReportCard;
use App\Models\Schedule;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;
use function PHPUnit\Framework\isFalse;

class ReportCardRepository implements ReportCardRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = ReportCard::class;

    public function store(BarcodeDTO $DTO) :Barcode
    {
        return Barcode::create([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);
    }

    public function update(Barcode $barcode, BarcodeDTO $DTO) :Barcode
    {
        $barcode->update([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);

        return $barcode;
    }

    public function delete(Barcode $barcode)
    {
        $barcode->delete();
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $query->where('good_id', $good->id);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('barcode', 'like', '%' . implode('%', $searchTerm) . '%');
    }

    public function getEmployeeQuery(array $data)
    {
        $filterParams = $this->model::filterData($data);
        $currentYear = now()->year;
        $month = (int)$data['month_id'];

        return Employee::query()
            ->select(['employees.id', 'employees.name', 'ws.number_of_hours', 'firings.firing_date', 'hirings.schedule_id'])
            ->join('hirings', 'hirings.employee_id', '=', 'employees.id')
            ->join('schedules as sc', 'sc.id', '=', 'hirings.schedule_id')
            ->join('worker_schedules as ws', 'ws.schedule_id', '=', 'sc.id')
            ->leftJoin('firings', function($join) use ($filterParams, $currentYear, $month) {
                $join->on('firings.employee_id', '=', 'employees.id')
                    ->where('firings.organization_id', '=', $filterParams['organization_id'])
                    ->whereYear('firings.firing_date', '=', $currentYear)
                    ->whereMonth('firings.firing_date', '=', $month);
            })
            ->where('ws.month_id', $month)
            ->where('hirings.organization_id', $filterParams['organization_id'])
            ->whereMonth('hirings.hiring_date', '<=', $month)
            ->whereYear('hirings.hiring_date', '=', $currentYear)
            ->paginate($filterParams['itemsPerPage']);

    }

    public function calculateWorkedHours($firingDate, $scheduleId)
    {
        if (empty($firingDate)) {
            return null; // Увольнения не было, выводим полное кол-во часов
        }


        $firingDay = Carbon::parse($firingDate)->day;
        $startOfMonth = Carbon::parse($firingDate)->startOfMonth();
        $endOfEmployment = Carbon::parse($firingDate)->startOfDay();


        $totalHours = 0;
        $schedule = Schedule::find($scheduleId);

        $dailyHours = $schedule->weekHours->pluck('hours', 'week')->toArray();

        for ($day = $startOfMonth; $day->lessThanOrEqualTo($endOfEmployment); $day->addDay()) {
            $weekDay = $day->dayOfWeekIso;
            dump($weekDay);
            $totalHours += $dailyHours[$weekDay] ?? 0;
        }

        return $totalHours;
    }

    public function getEmployees($data) {
      $employees = $this->getEmployeeQuery($data);

        foreach ($employees as $employee) {
            $workedHours = $this->calculateWorkedHours($employee->firing_date, $employee->schedule_id);
            $employee->worked_hours = $workedHours ?? $employee->number_of_hours;
        }

        return $employees;
    }



}
