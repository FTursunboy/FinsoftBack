<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface GoodReportRepositoryInterface
{
    public function index(array $data);

    public function topGoods(array $data);

    public function export(array $data);
}
