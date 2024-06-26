<?php

namespace App\Repositories\Plans\Contracts;

use App\DTO\Plan\EmployeeSalePlanDTO;
use App\DTO\Plan\ExpenseItemSalePlanDTO;
use App\Models\SalePlan;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExpenseItemSaleRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(ExpenseItemSalePlanDTO $dto);

    public function update(ExpenseItemSalePlanDTO $dto, SalePlan $plan);

    public function massDelete(array $data);


}
