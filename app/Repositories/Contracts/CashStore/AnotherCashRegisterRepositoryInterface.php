<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;
use App\Models\CashStore;

interface AnotherCashRegisterRepositoryInterface
{
    public function index(array $data);
    public function store(AnotherCashRegisterDTO $dto);
    public function update(CashStore $cashStore, AnotherCashRegisterDTO $dto);
}
