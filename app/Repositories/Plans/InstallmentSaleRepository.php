<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\InstallmentSalePlanDTO;
use App\Enums\PlanType;
use App\Models\InstallmentPlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\InstallmentSaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InstallmentSaleRepository implements InstallmentSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(InstallmentSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
            'type' => PlanType::Installment
        ]);

        foreach ($DTO->goods as $good) {
            InstallmentPlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'good_id' => $good['good_id'],
                    'month_id' => $good['month_id']
                ],
                [
                    'quantity' => $good['quantity']
                ]
            );
        }

        return $plan->load(['installmentSalePlan.month', 'installmentSalePlan.good', 'organization']);
    }


    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::Good);

        return $query->with(['installmentSalePlan.month', 'installmentSalePlan.good', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(InstallmentSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'organization_id' => $dto->organization_id,
            'year' => $dto->year,
            'type' => PlanType::Installment
        ]);

        foreach ($dto->goods as $good) {
            $goodsPlan = InstallmentPlan::where('sale_plan_id', $plan->id)
                ->where('good_id', $good['good_id'])
                ->where('month_id', $good['month_id'])
                ->first();

            if ($goodsPlan) {
                $goodsPlan->quantity = $good['quantity'];
                $goodsPlan->save();
            } else {
                InstallmentPlan::create([
                    'sale_plan_id' => $plan->id,
                    'good_id' => $good['good_id'],
                    'month_id' => $good['month_id'],
                    'quantity' => $good['quantity']
                ]);
            }
        }

        return $plan->load(['installmentSalePlan.month', 'installmentSalePlan.good', 'organization']);
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $plan = $this->model::where('id', $id)->first();

                $plan->installmentSalePlan()->delete();

                $plan->update([
                    'deleted_at' => Carbon::now(),
                ]);

            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
