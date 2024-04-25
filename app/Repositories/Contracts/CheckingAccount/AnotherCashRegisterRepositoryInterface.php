<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\AnotherCashRegisterDTO;
use App\Models\CheckingAccount;

interface AnotherCashRegisterRepositoryInterface
{
    public function index(array $data);
    public function store(AnotherCashRegisterDTO $dto);

    public function update(AnotherCashRegisterDTO $dto, CheckingAccount $checkingAccount);
}
