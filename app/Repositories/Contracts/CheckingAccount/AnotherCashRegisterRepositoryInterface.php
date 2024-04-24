<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\AnotherCashRegisterDTO;

interface AnotherCashRegisterRepositoryInterface
{
    public function index(array $data);
    public function store(AnotherCashRegisterDTO $dto);
}
