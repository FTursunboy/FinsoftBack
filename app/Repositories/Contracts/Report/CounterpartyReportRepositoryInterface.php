<?php

namespace App\Repositories\Contracts\Report;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Counterparty;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface CounterpartyReportRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

}
