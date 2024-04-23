<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\CreditReceiveDTO;

interface CreditReceiveRepositoryInterface
{
    public function index(array $data);
    public function store(CreditReceiveDTO $dto);
}
