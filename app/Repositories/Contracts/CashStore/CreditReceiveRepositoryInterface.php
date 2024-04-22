<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\CreditReceiveDTO;

interface CreditReceiveRepositoryInterface
{
    public function index(array $data);
    public function store(CreditReceiveDTO $dto);
}
