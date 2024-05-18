<?php

namespace App\Repositories\Report;

use App\Models\CounterpartySettlement;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;

class ReconciliationReportRepository implements ReconciliationReportRepositoryInterface
{
    use Sort, FilterTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $query = CounterpartySettlement::query();



        return $query->paginate($data['itemsPerPage']);
    }

    public function debtAtBegin($query, string $from)
    {
        $query::whereDate('date', '<', $from)
            ->selectRaaw('SUM');
    }

}
