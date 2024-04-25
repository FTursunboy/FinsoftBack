<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\CreditReceiveDTO;
use App\Models\CashStore;

interface CreditReceiveRepositoryInterface
{
    public function index(array $data);
    public function store(CreditReceiveDTO $dto);
    public function update(CashStore $cashStore, CreditReceiveDTO $dto);
}
