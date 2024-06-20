<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\GoodSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface GoodSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(GoodSalePlanDTO $dto);

    public function update(GoodSalePlanDTO $dto, SalePlan $plan);


}
