<?php

namespace App\Repositories\Report;

use App\Models\GoodAccounting;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class GoodReportRepository implements GoodReportRepositoryInterface
{
    public $model = GoodAccounting::class;

    public function index(array $data) :LengthAwarePaginator
    {
        $filterData = $this->model::filterData($data);
        $query = $this->buildQuery($filterData);

        return $query->paginate($filterData['itemsPerPage']);
    }

    public function export(array $data) :string
    {
        $filterData = $this->model::filterData($data);
        $result = $this->buildQuery($filterData)->get();

        return $this->createExcelFile($result);
    }


    private function createExcelFile(Collection $collection) :string
    {
        $filename = 'report ' . now() . '.xlsx';
        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Товар', 'Группа', 'Остаток на начало', 'Приход', 'Расход', 'Остаток на конец'
        ]);
        $writer->addRow($headerRow);

        foreach ($collection as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->group_name,
                $row->start_reminder,
                $row->income,
                $row->outcome,
                $row->remainder,
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;
    }

    private function buildQuery(array $filterData) : Builder
    {
        $query = GoodAccounting::query();

        $query->select([
            'goods.id',
            'goods.name',
            'good_groups.name as group_name',
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
