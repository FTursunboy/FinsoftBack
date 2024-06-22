<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\GoodSalePlanDTO;
use App\DTO\Plan\InstallmentSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface InstallmentSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(InstallmentSalePlanDTO $dto);

    public function update(InstallmentSalePlanDTO $dto, SalePlan $plan);


}
