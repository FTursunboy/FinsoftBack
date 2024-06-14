<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\WithdrawalDTO;
use App\Models\CashStore;

interface WithdrawalRepositoryInterface
{
    public function index(array $data);
    public function store(WithdrawalDTO $dto);
    public function update(CashStore $cashStore, WithdrawalDTO $dto);
    public function approve(array $ids);
}
