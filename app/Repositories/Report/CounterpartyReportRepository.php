<?php

namespace App\Repositories\Report;

use App\Enums\MovementTypes;
use App\Models\Counterparty;
use App\Models\CounterpartySettlement;
use App\Models\GoodAccounting;
use App\Repositories\Contracts\Report\CounterpartyReportRepositoryInterface;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CounterpartyReportRepository implements CounterpartyReportRepositoryInterface
{
    use Sort, FilterTrait;

    protected $model = CounterpartySettlement::class;


    public function index(array $data): LengthAwarePaginator
    {
        $income = MovementTypes::Income;
        $outcome = MovementTypes::Outcome;
        $query = $this->model::query();


        $query->select([
            'goods.id as good_id',
            'good_groups.id as group_id',
            DB::raw('SUM(CASE WHEN counterparty_settlements.movement_type = $income THEN counterparty_settlements.amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as outcome'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "приход" THEN good_accountings.amount ELSE 0 END) - SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as remainder'),
        ])
            ->join('goods', 'good_accountings.good_id', '=', 'goods.id')
            ->join('good_groups', 'good_groups.id', '=', 'goods.good_group_id')
            ->groupBy('goods.id', 'good_groups.id');


        return $query->filter($filterData);
    }
}
