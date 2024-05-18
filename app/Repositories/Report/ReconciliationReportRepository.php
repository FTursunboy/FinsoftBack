<?php

namespace App\Repositories\Report;

use App\Enums\MovementTypes;
use App\Models\Counterparty;
use App\Models\CounterpartySettlement;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReconciliationReportRepository implements ReconciliationReportRepositoryInterface
{
    use Sort, FilterTrait;

    public function index(Counterparty $counterparty, array $data): LengthAwarePaginator
    {
        $query = CounterpartySettlement::query()->where('counterparty_id', $counterparty->id);

        $query = $this->debtAtBegin($query, $data['from']);
dd($query->toRawSql());
        return $query->paginate($data['itemsPerPage']);
    }

    public function debtAtBegin($query, string $from)
    {
        $outcome = MovementTypes::Outcome->value;
        $income = MovementTypes::Income->value;

        return $query->whereDate('date', '<', $from)
            ->select('counterparty_settlements.counterparty_id',
                DB::raw("SUM(CASE WHEN movement_type = '$income' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' THEN sum ELSE 0 END) as debt"))
            ->groupBy('counterparty_settlements.counterparty_id');
    }

}
