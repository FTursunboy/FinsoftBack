<?php

namespace App\Repositories\Plans;

use App\DTO\BarcodeDTO;
use App\DTO\GoodSalePlanDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodPlan;
use App\Models\GoodSalePlan;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Plans\Contracts\GoodSaleRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class GoodSaleRepository implements GoodSaleRepositoryInterface
{

    public $model = GoodSalePlan::class;

    public function store(GoodSalePlanDTO $DTO)
    {
        $plan = GoodSalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
        ]);


        foreach ($DTO->goods as $good) {
            GoodPlan::updateOrCreate(
                [
                    'good_sale_plan_id' => $plan->id,
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

    public function update(GoodSalePlanDTO $dto, GoodSalePlan $plan)
    {
        $plan->update([
            'year' => $dto->year,
            'organization_id' => $dto->organization_id,
        ]);

        foreach ($dto->goods as $good) {
            $goodsPlan = GoodPlan::where('good_sale_plan_id', $plan->id)
                ->where('good_id', $good['good_id'])
                ->where('month_id', $good['month_id'])
                ->first();

            if ($goodsPlan) {
                $goodsPlan->quantity = $good['quantity'];
                $goodsPlan->save();
            } else {
                GoodPlan::create([
                    'good_sale_plan_id' => $plan->id,
                    'good_id' => $good['good_id'],
                    'month_id' => $good['month_id'],
                    'quantity' => $good['quantity']
                ]);
            }
        }

        return $plan->load(['goodSalePlan.month', 'goodSalePlan.good', 'organization']);
    }
}
