<?php

namespace App\Repositories\Contracts\Report;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Counterparty;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReconciliationReportRepositoryInterface
{
    public function index(Counterparty $counterparty, array $data) :LengthAwarePaginator;

    public function debts(Counterparty $counterparty, array $data);

    public function export(Counterparty $counterparty, array $data);
}
