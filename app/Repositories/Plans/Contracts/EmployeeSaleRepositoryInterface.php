<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(EmployeeSalePlanDTO $dto);

    public function update(EmployeeSalePlanDTO $dto, SalePlan $plan);

    public function massDelete(array $data);


}
