<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\WithdrawalDTO;

interface WithdrawalRepositoryInterface
{
    public function index(array $data);
    public function store(WithdrawalDTO $dto);
}
