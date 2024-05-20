<?php

namespace App\Repositories\Report;

use App\Enums\MovementTypes;
use App\Models\Counterparty;
use App\Models\CounterpartySettlement;
use App\Models\GoodAccounting;
use App\Models\Role;
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

        $filterData =  $this->model::filterData($data);

       $query->select([
            'cur.id as currency_id',
            'cp.id as counterparty_id',
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$income}' THEN counterparty_settlements.sum ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$outcome}' THEN counterparty_settlements.sum ELSE 0 END) as outcome"),
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$outcome}' THEN counterparty_settlements.sum ELSE 0 END) - SUM(CASE WHEN counterparty_settlements.movement_type = '{$income}' THEN counterparty_settlements.sum ELSE 0 END) as debt"),
        ])
            ->join('counterparties as cp', 'counterparty_settlements.counterparty_id', '=', 'cp.id')
            ->join('currencies as cur', 'counterparty_settlements.currency_id', '=', 'cur.id')
            ->join('counterparty_roles as cr', 'cp.id', '=', 'cr.counterparty_id')
            ->join('user_roles as ur', 'cr.role_id', '=', 'ur.id')
            ->where('ur.name', Role::SUPPLIER)
            ->groupBy('cp.id');

       if (isset($filterData['sort'])) {
           $query->orderBy($filterData['sort'], $filterData['direction']);
       }

         $query->filter($filterData);



        return $query->paginate($filterData['itemsPerPage']);



    }
}
