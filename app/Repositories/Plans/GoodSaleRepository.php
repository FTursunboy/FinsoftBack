<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\GoodSalePlanDTO;
use App\Enums\PlanType;
use App\Models\GoodPlan;
use App\Models\SalePlan;
use App\Repositories\Plans\Contracts\GoodSaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GoodSaleRepository implements GoodSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(GoodSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
            'type' => PlanType::Good
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

        $query = $this->model::where('type', PlanType::Good);

        return $query->with(['goodSalePlan.month', 'goodSalePlan.good', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(GoodSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'year' => $dto->year,
            'organization_id' => $dto->organization_id,
            'type' => PlanType::Good
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

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $plan = $this->model::where('id', $id)->first();

                $plan->goodSalePlan()->delete();

                $plan->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
