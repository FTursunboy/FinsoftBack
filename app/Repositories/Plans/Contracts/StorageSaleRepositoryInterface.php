<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\StorageSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface StorageSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(StorageSalePlanDTO $dto);

    public function update(StorageSalePlanDTO $dto, SalePlan $plan);

}
