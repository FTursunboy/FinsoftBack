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
    }
}
