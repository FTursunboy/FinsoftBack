<?php

namespace App\Repositories\Plans;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\StorageSalePlanDTO;
use App\Enums\PlanType;
use App\Models\EmployeePlan;
use App\Models\SalePlan;
use App\Models\StoragePlan;
use App\Repositories\Plans\Contracts\EmployeeSaleRepositoryInterface;

use App\Repositories\Plans\Contracts\StorageSaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StorageSaleRepository implements StorageSaleRepositoryInterface
{
    public $model = SalePlan::class;

    public function store(StorageSalePlanDTO $DTO)
    {
        $plan = SalePlan::create([
            'year' => $DTO->year,
            'organization_id' => $DTO->organization_id,
            'type' => PlanType::Storage
        ]);

        foreach ($DTO->storages as $storage) {
            StoragePlan::updateOrCreate(
                [
                    'sale_plan_id' => $plan->id,
                    'storage_id' => $storage['storage_id'],
                    'month_id' => $storage['month_id']
                ],
                [
                    'sum' => $storage['sum']
                ]
            );
        }

        return $plan->load(['storageSalePlan.month', 'storageSalePlan.storage', 'organization']);
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::where('type', PlanType::Storage);

        return $query->with(['storageSalePlan.month', 'storageSalePlan.storage', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function update(StorageSalePlanDTO $dto, SalePlan $plan)
    {
        $plan->update([
            'year' => $dto->year,
            'organization_id' => $dto->organization_id,
            'type' => PlanType::Storage
        ]);

        foreach ($dto->storages as $storage) {
            $storagesPlan = StoragePlan::where('sale_plan_id', $plan->id)
                ->where('storage_id', $storage['storage_id'])
                ->where('month_id', $storage['month_id'])
                ->first();

            if ($storagesPlan) {
                $storagesPlan->sum = $storage['sum'];
                $storagesPlan->save();
            } else {
                StoragePlan::create([
                    'sale_plan_id' => $plan->id,
                    'storage_id' => $storage['storage_id'],
                    'month_id' => $storage['month_id'],
                    'sum' => $storage['sum']
                ]);
            }
        }

        return $plan->load(['storageSalePlan.month', 'storageSalePlan.storage', 'organization']);
    }

    public function massDelete(array $ids)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::transaction(function () use ($ids) {

            foreach ($ids['ids'] as $id) {
                $plan = $this->model::where('id', $id)->first();

                $plan->storageSalePlan()->delete();

                $plan->update([
                    'deleted_at' => Carbon::now(),
                ]);

            }

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
