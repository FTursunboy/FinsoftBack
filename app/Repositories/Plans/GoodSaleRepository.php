<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\GoodSalePlanDTO;
use App\Models\GoodPlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\GoodSaleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GoodSaleRepository implements GoodSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(GoodSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
        ]);


        foreach ($DTO->goods as $good) {
            GoodPlan::updateOrCreate(
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

        return $plan->load(['goodSalePlan.month', 'goodSalePlan.good', 'organization']);
    }


    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        return $this->model::with(['goodSalePlan.month', 'goodSalePlan.good', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(GoodSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'year' => $dto->year,
            'organization_id' => $dto->organization_id,
        ]);

        foreach ($dto->goods as $good) {
            $goodsPlan = GoodPlan::where('sale_plan_id', $plan->id)
                ->where('good_id', $good['good_id'])
                ->where('month_id', $good['month_id'])
                ->first();

            if ($goodsPlan) {
                $goodsPlan->quantity = $good['quantity'];
                $goodsPlan->save();
            } else {
                GoodPlan::create([
                    'sale_plan_id' => $plan->id,
                    'good_id' => $good['good_id'],
                    'month_id' => $good['month_id'],
                    'quantity' => $good['quantity']
                ]);
            }
        }

        return $plan->load(['goodSalePlan.month', 'goodSalePlan.good', 'organization']);
    }
}
