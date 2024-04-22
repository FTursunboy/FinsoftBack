<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\WithdrawalDTO;

interface WithdrawalRepositoryInterface
{
    public function index(array $data);
    public function store(WithdrawalDTO $dto);
}
