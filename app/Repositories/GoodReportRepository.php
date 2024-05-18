<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Exports\GoodAccountingExport;
use App\Models\Barcode;
use App\Models\Document;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Carbon\Carbon;
use Google\Service\Gmail\History;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class GoodReportRepository implements GoodReportRepositoryInterface
{
    public $model = GoodAccounting::class;

    public function index(array $data)
    {
        $filterData = $this->model::filterData($data);
        $query = $this->getQuery($filterData);

        return $query->paginate($filterData['itemsPerPage']);
    }


    public function export(array $data) :Collection
    {
        $filterData = $this->model::filterData($data);

        return $this->getQuery($filterData)->get();
    }

    private function getQuery(array $filterData) : Builder
    {
        $query = GoodAccounting::query();

        $query->select([
            'goods.id as good_id',
            'good_groups.id as group_id',
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "приход" THEN good_accountings.amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as outcome'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "приход" THEN good_accountings.amount ELSE 0 END) - SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as remainder'),
        ])
            ->join('goods', 'good_accountings.good_id', '=', 'goods.id')
            ->join('good_groups', 'good_groups.id', '=', 'goods.good_group_id')
            ->groupBy('goods.id', 'good_groups.id');


        return $query->filter($filterData);

    }
}
