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

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class GoodReportRepository implements GoodReportRepositoryInterface
{
    public $model = GoodAccounting::class;

    public function index(array $data)
    {
        $good = $this->model::filter();


        \DB::table('good_accountings as ga')
            ->join('goods as g', 'g.id', 'ga.good_id')
            ->join('groups as gr', 'gr.id', 'g.group_id')
            ->select('g.id', 'g.group_id', 'ga.amount', 'ga.amount', 'g');


    }
}
