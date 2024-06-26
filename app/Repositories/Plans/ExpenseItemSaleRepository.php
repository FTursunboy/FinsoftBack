<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\ExpenseItemSalePlanDTO;
use App\Enums\PlanType;
use App\Models\EmployeePlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;

use App\Repositories\Plans\Contracts\ExpenseItemSaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ExpenseItemSaleRepository implements ExpenseItemSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(ExpenseItemSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'organization_id' => $DTO->organization_id,
            'year' => $DTO->year,
            'type' => PlanType::ExpenseItem
        ]);

        foreach ($DTO->expenseItems as $expenseItem) {
            EmployeePlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'employee_id' => $expenseItem['expense_item_id'],
                    'month_id' => $expenseItem['month_id']
                ],
                [
                    'sum' => $expenseItem['sum']
                ]
            );
        }

        return $plan->load(['expenseItemSalePlan.month', 'expenseItemSalePlan.employee', 'organization']);
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::Employee);

        return $query->with(['expenseItemSalePlan.month', 'expenseItemSalePlan.employee', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(ExpenseItemSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'organization_id' => $dto->organization_id,
            'year' => $dto->year,
            'type' => PlanType::ExpenseItem
        ]);

        foreach ($dto->expenseItems as $expenseItem) {
            $expenseItemPlan = EmployeePlan::where('sale_plan_id', $plan->id)
                ->where('expense_item_id', $expenseItem['expense_item_id'])
                ->where('month_id', $expenseItem['month_id'])
                ->first();

            if ($expenseItemPlan) {
                $expenseItemPlan->sum = $expenseItem['sum'];
                $expenseItemPlan->save();
            } else {
                EmployeePlan::create([
                    'sale_plan_id' => $plan->id,
                    'expense_item_id' => $expenseItem['expense_item_id'],
                    'month_id' => $expenseItem['month_id'],
                    'sum' => $expenseItem['sum']
                ]);
            }
        }

        return $plan->load(['expenseItemSalePlan.month', 'expenseItemSalePlan.good', 'organization']);
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $plan = $this->model::where('id', $id)->first();

                $plan->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
