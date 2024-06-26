<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\OldNewClientSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface OldNewClientSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(OldNewClientSalePlanDTO $dto);

    public function update(OldNewClientSalePlanDTO $dto, SalePlan $plan);

    public function massDelete(array $data);

}
