<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\SalaryPaymentDTO;
use App\Models\CashStore;

interface SalaryPaymentRepositoryInterface
{
    public function index(array $data);
    public function store(SalaryPaymentDTO $dto);
    public function update(CashStore $cashStore, SalaryPaymentDTO $dto);
}
