<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\PriceSetUpDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\PriceSetUp;
use Illuminate\Pagination\LengthAwarePaginator;

interface PriceSetUpRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(PriceSetUpDTO $dto) :PriceSetUp;

    public function update(PriceSetUp $barcode, PriceSetUpDTO $DTO) :PriceSetUp;

    public function delete(Barcode $barcode);

}
