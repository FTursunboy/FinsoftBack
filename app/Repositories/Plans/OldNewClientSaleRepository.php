<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\OldNewClientSalePlanDTO;
use App\Enums\PlanType;
use App\Models\EmployeePlan;
use App\Models\OldNewClientPlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;

use App\Repositories\Plans\Contracts\OldNewClientSaleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OldNewClientSaleRepository implements OldNewClientSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(OldNewClientSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
            'type' => PlanType::OldNewClient
        ]);

        foreach ($DTO->oldNewClients as $oldNewClient) {
            OldNewClientPlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'month_id' => $oldNewClient['month_id']
                ],
                [
                    'new_client' => $oldNewClient['new_client'],
                    'old_client' => $oldNewClient['old_client'],
                ]
            );
        }

        return $plan->load(['olNewClientSalePlan.month', 'organization']);
    }


    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::OldNewClient);

        return $query->with(['oldNewClientSalePlan.month', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(OldNewClientSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'organization_id' => $dto->organization_id,
            'year' => $dto->year,
            'type' => PlanType::OldNewClient
        ]);

        foreach ($dto->oldNewClients as $oldNewClient) {
            $oldNewClientPlan = OldNewClientPlan::where('sale_plan_id', $plan->id)
                ->where('month_id', $oldNewClient['month_id'])
                ->first();

            if ($oldNewClientPlan) {
                $oldNewClientPlan->new_client = $oldNewClient['new_client'];
                $oldNewClientPlan->old_client = $oldNewClient['old_client'];
                $oldNewClientPlan->save();
            } else {
                OldNewClientPlan::create([
                    'sale_plan_id' => $plan->id,
                    'month_id' => $oldNewClient['month_id'],
                    'new_client' => $oldNewClient['new_client'],
                    'old_client' => $oldNewClient['old_client'],
                ]);
            }
        }

        return $plan->load(['oldNewClientSalePlan.month', 'organization']);
    }
}
