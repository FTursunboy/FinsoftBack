<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\WithdrawalDTO;
use App\Models\CheckingAccount;

interface WithdrawalRepositoryInterface
{
    public function index(array $data);
    public function store(WithdrawalDTO $dto);

    public function update(WithdrawalDTO $dto, CheckingAccount $account);
}
