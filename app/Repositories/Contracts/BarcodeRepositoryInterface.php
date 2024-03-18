<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface BarcodeRepositoryInterface
{
    public function index(Good $good, array $data) :LengthAwarePaginator;

    public function store(BarcodeDTO $dto) :Barcode;

    public function update(Barcode $barcode, BarcodeDTO $DTO) :Barcode;

    public function delete(Barcode $barcode);

}
