<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\OperationTypeSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface OperationTypeSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(OperationTypeSalePlanDTO $dto);

    public function update(OperationTypeSalePlanDTO $dto, SalePlan $plan);


}
