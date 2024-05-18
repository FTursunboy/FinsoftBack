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

    protected $model = CounterpartySettlement::class;

    public function index(Counterparty $counterparty, array $data): LengthAwarePaginator
    {
        $data = $this->model::filterData($data);

        $query = $this->model::query()
            ->where([
                ['counterparty_id', $counterparty->id],
                ['date', '>=', $data['from']],
                ['date', '<=', $data['to']]]);

        $query = $this->sort($data, $query, ['goodAccounting', 'goodAccounting.good', 'counterparty', 'counterpartyAgreement', 'organization']);

        return $query->paginate($data['itemsPerPage']);
    }

    public function getData($query, string $from)
    {
        $outcome = MovementTypes::Outcome->value;
        $income = MovementTypes::Income->value;

        return $query->select('counterparty_settlements.counterparty_id',
            DB::raw("SUM(CASE WHEN movement_type = '$income' and date < '$from' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date < '$from' THEN sum ELSE 0 END) as debt"),
            DB::raw("SUM(CASE WHEN movement_type = '$income' and date >= '$from' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date >= '$from' THEN sum ELSE 0 END) -
                    (SUM(CASE WHEN movement_type = '$income' and date < '$from' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date < '$from' THEN sum ELSE 0 END)) as debt_at_end
                    ")
        )->groupBy('counterparty_settlements.counterparty_id');
    }

}
