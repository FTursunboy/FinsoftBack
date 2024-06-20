<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\GoodSalePlanDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodSalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface GoodSaleRepositoryInterface
{
    public function index() :LengthAwarePaginator;

    public function store(GoodSalePlanDTO $dto);

    public function update(GoodSalePlanDTO $dto, GoodSalePlan $plan);


}
