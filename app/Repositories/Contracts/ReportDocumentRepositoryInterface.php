<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Repositories\Contracts\Document\Documentable;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReportDocumentRepositoryInterface
{
    public function getBalances(Documentable $documentable, array $data) :LengthAwarePaginator;

}
