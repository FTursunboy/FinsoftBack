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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportCardRepository implements ReportCardRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = ReportCard::class;

    public function store(BarcodeDTO $DTO): Barcode
    {
        return Barcode::create([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);
    }

    public function update(Barcode $barcode, BarcodeDTO $DTO): Barcode
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

    public function getEmployeeQuery(array $filterParams)
    {

        $currentYear = now()->year;
        $month = $filterParams['month_id'];


        return Employee::query()
            ->select(['employees.id', 'employees.name', 'ws.number_of_hours',
                'firings.firing_date', 'hirings.schedule_id', 'employee_movements.movement_date',
                'employee_movements.schedule_id as new_schedule_id', 'hirings.salary', 'employee_movements.salary as new_salary', 'hirings.hiring_date'])
            ->join('hirings', 'hirings.employee_id', '=', 'employees.id')
            ->join('schedules as sc', 'sc.id', '=', 'hirings.schedule_id')
            ->join('worker_schedules as ws', 'ws.schedule_id', '=', 'sc.id')
            ->leftJoin('firings', function ($join) use ($filterParams, $currentYear, $month) {
                $join->on('firings.employee_id', '=', 'employees.id')
                    ->where('firings.organization_id', '=', $filterParams['organization_id'])
                    ->whereYear('firings.firing_date', '=', $currentYear)
                    ->whereMonth('firings.firing_date', '=', $month);
            })
            ->leftJoin('employee_movements', function ($join) use ($filterParams, $currentYear, $month) {
                $join->on('employee_movements.employee_id', '=', 'employees.id')
                    ->whereYear('employee_movements.movement_date', '=', $currentYear)
                    ->whereMonth('employee_movements.movement_date', '=', $month);
            })
            ->where('ws.month_id', $month)
            ->where('hirings.organization_id', $filterParams['organization_id'])
            ->whereMonth('hirings.hiring_date', '<=', $month)
            ->whereYear('hirings.hiring_date', '=', $currentYear);
    }

    private function calculateWorkedHours($firingDate, $scheduleId)
    {
        if (empty($firingDate)) {
            return null;
        }



        $firingDay = Carbon::parse($firingDate)->day;
        $startOfMonth = Carbon::parse($firingDate)->startOfMonth();
        $endOfEmployment = Carbon::parse($firingDate)->startOfDay();


        $totalHours = 0;
        $schedule = Schedule::find($scheduleId);

        $dailyHours = $schedule->weekHours->pluck('hours', 'week')->toArray();

        for ($day = $startOfMonth; $day->lessThanOrEqualTo($endOfEmployment); $day->addDay()) {
            $weekDay = $day->dayOfWeekIso - 1;

            $totalHours += $dailyHours[$weekDay] ?? 0;
        }

        return $totalHours;
    }

    private function calculateMovementHours($employees)
    {
        $modifiedEmployees = [];

        foreach ($employees as $employee) {

            if (!empty($employee->movement_date)) {
                if ($employee->schedule_id !== $employee->new_schedule_id || $employee->salary !== $employee->new_salary) {
                    $workedHoursBeforeMovement = $this->calculateWorkedHoursBeforeMovement($employee->movement_date, $employee->schedule_id);
                    $workedHoursAfterMovement = $this->calculateWorkedHoursAfterMovement($employee->movement_date, $employee->new_schedule_id);

                    $modifiedEmployee = clone $employee;
                    $modifiedEmployee->number_of_hours = $workedHoursBeforeMovement;

                    $modifiedEmployees[] = $modifiedEmployee;

                   $modifiedEmployees[] = (object)[
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'number_of_hours' => $workedHoursAfterMovement,
                        'firing_date' => $employee->firing_date,
                        'schedule_id' => $employee->new_schedule_id,
                        'new_schedule_id' => $employee->new_schedule_id,
                        'salary' => $employee->salary,
                        'new_salary' => $employee->new_salary,
                        'hiring_date' => $employee->hiring_date,
                    ];
                }
            }
        }

        $employees = collect($modifiedEmployees);
        return $employees;
    }

    private function calculateWorkedHoursBeforeMovement($movementDate, $scheduleId)
    {
        $movementDay = Carbon::parse($movementDate)->day;


        $startOfMonth = Carbon::parse($movementDate)->startOfMonth();
        $endOfMonth = Carbon::parse($movementDate)->endOfMonth();


        $totalHours = 0;
        $schedule = Schedule::find($scheduleId);
        $dailyHours = $schedule->weekHours->pluck('hours', 'week')->toArray();

        for ($day = $startOfMonth; $day->day < $movementDay; $day->addDay()) {
            $weekDay = $day->dayOfWeekIso - 1;

            $totalHours += $dailyHours[$weekDay] ?? 0;
        }

        return $totalHours;
    }

    private function calculateWorkedHoursAfterMovement($movementDate, $scheduleId)
    {

        $startOfMonth = Carbon::parse($movementDate)->startOfMonth();
        $endOfMonth = Carbon::parse($movementDate)->endOfMonth();


        $movementDay = Carbon::parse($movementDate)->day;

        $totalHours = 0;
        $schedule = Schedule::find($scheduleId);
        $dailyHours = $schedule->weekHours->pluck('hours', 'week')->toArray();

        for ($day = $startOfMonth; $day->lessThanOrEqualTo($endOfMonth); $day->addDay()) {
            if ($day->day >= $movementDay) {
                $weekDay = $day->dayOfWeekIso - 1;
                $totalHours += $dailyHours[$weekDay] ?? 0;
            }
        }

        return $totalHours;
    }



    public function getEmployees($data)
    {
        $filterParams = $this->model::filterData($data);

        $employees = $this->getEmployeeQuery($filterParams);

        foreach ($employees as $employee) {
            $workedHours = $this->calculateWorkedHours($employee->firing_date, $employee->schedule_id);
            $employee->number_of_hours = $workedHours ?? $employee->number_of_hours;
        }

        return $this->calculateMovementHours($employees->get());
    }


}
