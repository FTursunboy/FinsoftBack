<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Month;
use App\Models\Organization;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReportCardRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(BarcodeDTO $dto) :Barcode;

    public function update(Barcode $barcode, BarcodeDTO $DTO) :Barcode;

    public function delete(Barcode $barcode);

    public function getEmployees(Organization $organization, Month $month);

}
