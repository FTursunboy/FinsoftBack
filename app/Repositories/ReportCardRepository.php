<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\ReportCardDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\ReportCard;
use App\Models\Schedule;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportCardRepository implements ReportCardRepositoryInterface
{
    use Sort, FilterTrait, DocNumberTrait;

    public $model = ReportCard::class;

    public function store(ReportCardDTO $DTO)
    {
        $model = $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'date' => $DTO->date,
            'organization_id' => $DTO->organization_id,
            'month_id' => $DTO->month_id,
            'author_id' => \Auth::id(),
            'comment' => $DTO->comment
        ]);

        $this->insertReportCardEmployees($DTO->data, $model);

        return $model;
    }

    private function insertReportCardEmployees(array $data, ReportCard $model)
    {

        $array_to_insert = array_map(function ($item) use ($data, $model) {
            return [
                'report_card_id' => $model->id,
                'standart_hours' => $item['standart_hours'],
                'fact_hours' => $item['fact_hours'],
                'employee_id' => $item['employee_id'],
                'schedule_id' => $item['schedule_id'],
                'salary' => $item['salary']
            ];
        }, $data);

        \DB::table('report_employees')->insert($array_to_insert);
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
        $filterParams = $this->model::filterData($data);


        return $this->model::paginate($filterParams['itemsPerPage']);
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
                    ->whereYear('firings.firing_date', '=', $currentYear);
            })
            ->leftJoin('employee_movements', function ($join) use ($filterParams, $currentYear, $month) {
                $join->on('employee_movements.employee_id', '=', 'employees.id')
                    ->whereYear('employee_movements.movement_date', '=', $currentYear)
                    ->whereMonth('employee_movements.movement_date', '<=', $month);
            })
            ->where('ws.month_id', $month)
            ->where('hirings.organization_id', $filterParams['organization_id'])
            ->whereMonth('hirings.hiring_date', '<=', $month)
            ->whereYear('hirings.hiring_date', '=', $currentYear)
            ->where(function (Builder $query) use($month) {
                $query->whereNull('firing_date')->orWhereMonth('firing_date', '>=', $month);
            })
           ->where(function (Builder $query) use($month) {
               $query->whereNull('movement_date')->orWhereMonth('movement_date', '>=', $month);
           });

    }

    private function calculateWorkedHours($firingDate, $scheduleId, int $month_id)
    {
        $firingMonth = Carbon::parse($firingDate)->month;


        if (empty($firingDate) || $firingMonth !== $month_id) {
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
        $modifiedEmployees = $employees;

        foreach ($employees as $employee) {
            if (!empty($employee->movement_date)) {
                if ($employee->schedule_id !== $employee->new_schedule_id || $employee->salary !== $employee->new_salary) {
                    $workedHoursBeforeMovement = $this->calculateWorkedHoursBeforeMovement($employee->movement_date, $employee->schedule_id);
                    $workedHoursAfterMovement = $this->calculateWorkedHoursAfterMovement($employee->movement_date, $employee->new_schedule_id);

                    $employee->number_of_hours = $workedHoursBeforeMovement;

                    $modifiedEmployees[] = (object)[
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'number_of_hours' => $workedHoursAfterMovement,
                        'firing_date' => $employee->firing_date,
                        'schedule_id' => $employee->schedule_id,
                        'salary' => $employee->salary,
                        'new_salary' => $employee->new_salary,
                        'hiring_date' => $employee->hiring_date,
                    ];
                }
            }
        }

       // $employees = collect($modifiedEmployees);
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


    public function getEmployees(array $data) :Collection
    {
        $filterParams = $this->model::filterData($data);

        $employees = $this->getEmployeeQuery($filterParams);

        $employees = $employees->get();

        foreach ($employees as $employee) {
            $workedHours = $this->calculateWorkedHours($employee->firing_date, $employee->schedule_id, $filterParams['month_id']);
            $employee->number_of_hours = $workedHours ?? $employee->number_of_hours;
        }

        return $this->calculateMovementHours($employees);
    }



    public function getEmployeesSalary(array $data)
    {
        $month_id = $data['month_id'];
        $organization_id = $data['organization_id'];

         dd( ReportCard::where('month_id', $month_id)->where('organization_id', $organization_id)
            ->join('employees as emp', 'emp.id', 'report_card.id')
            ->join('schedules as sc', 'sc.id', '=', 'report_cards.schedule_id')
            ->join('worker_schedules as ws', 'ws.schedule_id', '=', 'sc.id')
            ->join('report_employees as rp', 'rp.id', '=', 'report_card.id')
            ->select(['emp.id', 'rp.salary', 'rp.standart_hours', 'rp.fact_hours'])->toRawSql());

    }
}
