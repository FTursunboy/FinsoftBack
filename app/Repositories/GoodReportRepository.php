<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isFalse;

class GoodReportRepository implements GoodReportRepositoryInterface
{
    public $model = GoodAccounting::class;

    public function index(array $data)
    {
        $filterData = $this->model::filterData($data);

        $reports = GoodAccounting::select([
            'goods.id as good_id',
            'good_groups.id as group_id',
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "приход" THEN good_accountings.amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as outcome'),
            DB::raw('SUM(CASE WHEN good_accountings.movement_type = "приход" THEN good_accountings.amount ELSE 0 END) - SUM(CASE WHEN good_accountings.movement_type = "расход" THEN good_accountings.amount ELSE 0 END) as remainder'),
        ])
            ->join('goods', 'good_accountings.good_id', '=', 'goods.id')
            ->join('good_groups', 'good_groups.id', 'goods.good_group_id')
            ->whereNull('good_accountings.deleted_at')
            ->groupBy('goods.id');

        if (isset($filterData['start_date'])) {
            $date = Carbon::parse($filterData['start_date']);
            $reports->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as start_remainder'));
            $reports->addBinding([$date, $date], 'select');
        }

        if (isset($filterData['end_date'])) {
            $date = Carbon::parse($filterData['end_date']);
            $reports->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as end_remainder'));
            $reports->addBinding([$date, $date], 'select');
        }

        if (isset($filterData['date'])) {
            $date = Carbon::parse($filterData['date']);
            $reports->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as end_remainder'));
            $reports->addBinding([$date, $date], 'select');
            $reports->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as start_remainder'));
            $reports->addBinding([$date, $date], 'select');
        }

        return $reports->paginate($filterData['itemsPerPage']);
    }

}
