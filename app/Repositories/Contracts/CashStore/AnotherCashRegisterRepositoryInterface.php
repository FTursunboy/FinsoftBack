<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\AnotherCashRegisterDTO;

interface AnotherCashRegisterRepositoryInterface
{
    public function index(array $data);
    public function store(AnotherCashRegisterDTO $dto);
}
