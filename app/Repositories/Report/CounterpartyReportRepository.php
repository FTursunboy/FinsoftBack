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
        $income = MovementTypes::Income->value;
        $outcome = MovementTypes::Outcome->value;
        $query = $this->model::query();

        $query = $query->select([
            'goods.id as good_id',
            'good_groups.id as group_id',
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$income}' THEN counterparty_settlements.amount ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$outcome}' THEN counterparty_settlements.amount ELSE 0 END) as outcome"),
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$income}' THEN counterparty_settlements.amount ELSE 0 END) - SUM(CASE WHEN counterparty_settlements.movement_type = '{$outcome}' THEN counterparty_settlements.amount ELSE 0 END) as remainder"),
        ])
        ->join('counterparties as cp', 'counterparty_settlements.counterparty_id', '=', 'cp.id')
            ->join('currencies as cur', 'counterparty_settlements.currency_id', '=', 'cur.id')
            ->groupBy('goods.id', 'good_groups.id');

        dd($query->get());



    }
}
