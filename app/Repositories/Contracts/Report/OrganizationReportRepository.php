<?php

namespace App\Repositories\Contracts\Report;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Counterparty;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrganizationReportRepository
{
    public function index(array $data) :LengthAwarePaginator;

    public function export(array $data) :string;

}
