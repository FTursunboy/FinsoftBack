<?php

namespace App\Repositories\Report;

use App\Enums\MovementTypes;
use App\Models\Counterparty;
use App\Repositories\Contracts\Report\OrganizationReportRepository as OrganizationReportRepositoryInterface;
use App\Models\CounterpartySettlement;
use App\Models\GoodAccounting;
use App\Models\Role;
use App\Repositories\Contracts\Report\CounterpartyReportRepositoryInterface;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrganizationReportRepository implements OrganizationReportRepositoryInterface
{
    protected $model = CounterpartySettlement::class;


    public function index(array $data): LengthAwarePaginator
    {
        $income = MovementTypes::Income->value;

        $query = $this->model::query();

        $filterData = $this->model::filterData($data);
        $query->select([
            DB::raw("counterparty_settlements.date"),
            DB::raw("SUM(CASE WHEN counterparty_settlements.movement_type = '{$income}' THEN counterparty_settlements.sale_sum ELSE 0 END) as income"),
        ]);
        $query->groupBy(DB::raw("counterparty_settlements.date"));
        $query->filter($filterData);


        $pagination = $query->paginate($filterData['itemsPerPage']);

        $totalIncome = $this->model::query()
            ->where('movement_type', $income)
            ->when($filterData['startDate'], function ($query) use ($filterData) {
                $date = Carbon::parse($filterData['startDate']);
                return $query->where('date', '>=', $date);
            })
            ->when($filterData['endDate'], function ($query) use ($filterData) {
                $date = Carbon::parse($filterData['endDate']);
                return $query->where('date', '<=', $date);
            })
            ->sum('sale_sum');


        $dataWithTotalIncome = $pagination->getCollection()->map(function ($item) use ($totalIncome) {
            $item->total_income = $totalIncome;
            return $item;
        });

        $pagination->setCollection($dataWithTotalIncome);


        return $pagination;
    }

    public function export(array $data): string
    {
        return "report/djdjd.xlsx";
    }
}
