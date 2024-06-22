<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\OperationTypeSalePlanDTO;
use App\Enums\PlanType;
use App\Models\EmployeePlan;
use App\Models\OperationTypePlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;

use App\Repositories\Plans\Contracts\OperationTypeSaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OperationTypeSaleRepository implements OperationTypeSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(OperationTypeSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'organization_id' => $DTO->organization_id,
            'year' => $DTO->year,
            'type' => PlanType::OperationType
        ]);

        foreach ($DTO->operationTypes as $operationType) {
            OperationTypePlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'operation_type_id' => $operationType['operation_type_id'],
                    'month_id' => $operationType['month_id']
                ],
                [
                    'sum' => $operationType['sum']
                ]
            );
        }

        return $plan->load(['operationTypeSalePlan.month', 'operationTypeSalePlan.employee', 'organization']);
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::OperationType);

        return $query->with(['operationTypeSalePlan.month', 'operationTypeSalePlan.employee', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(OperationTypeSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'organization_id' => $dto->organization_id,
            'year' => $dto->year,
            'type' => PlanType::OperationType
        ]);

        foreach ($dto->operationTypes as $operationType) {
            $operationTypesPlan = EmployeePlan::where('sale_plan_id', $plan->id)
                ->where('operation_type_id', $operationType['operation_type_id'])
                ->where('month_id', $operationType['month_id'])
                ->first();

            if ($operationTypesPlan) {
                $operationTypesPlan->sum = $operationType['sum'];
                $operationTypesPlan->save();
            } else {
                OperationTypePlan::create([
                    'sale_plan_id' => $plan->id,
                    'operation_type_id' => $operationType['operation_type_id_id'],
                    'month_id' => $operationType['month_id'],
                    'sum' => $operationType['sum']
                ]);
            }
        }

        return $plan->load(['operationTypeSalePlan.month', 'operationTypeSalePlan.good', 'organization']);
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {
            foreach ($ids['ids'] as $id) {
                $plan = $this->model::where('id', $id)->first();

                $plan->operationTypeSalePlan()->delete();

                $plan->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
