<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\Enums\PlanType;
use App\Models\EmployeePlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;

use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeSaleRepository implements EmployeeSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(EmployeeSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'organization_id' => $DTO->organization_id,
            'year' => $DTO->year,
            'type' => PlanType::Employee
        ]);

        foreach ($DTO->employees as $employee) {
            EmployeePlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'employee_id' => $employee['employee_id'],
                    'month_id' => $employee['month_id']
                ],
                [
                    'sum' => $employee['sum']
                ]
            );
        }

        return $plan->load(['employeeSalePlan.month', 'employeeSalePlan.employee', 'organization']);
    }


    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::Employee);

        return $query->with(['employeeSalePlan.month', 'employeeSalePlan.employee', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(EmployeeSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'organization_id' => $dto->organization_id,
            'year' => $dto->year,
            'type' => PlanType::Employee
        ]);

        foreach ($dto->employees as $employee) {
            $employeesPlan = EmployeePlan::where('sale_plan_id', $plan->id)
                ->where('employee_id', $employee['employee_id'])
                ->where('month_id', $employee['month_id'])
                ->first();

            if ($employeesPlan) {
                $employeesPlan->sum = $employee['sum'];
                $employeesPlan->save();
            } else {
                EmployeePlan::create([
                    'sale_plan_id' => $plan->id,
                    'employee_id' => $employee['employee_id'],
                    'month_id' => $employee['month_id'],
                    'sum' => $employee['sum']
                ]);
            }
        }

        return $plan->load(['employeeSalePlan.month', 'employeeSalePlan.good', 'organization']);
    }
}
