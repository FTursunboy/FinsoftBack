<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;
use App\Models\CashStore;

interface AccountablePersonRefundRepositoryInterface
{
    public function index(array $data);
    public function store(AccountablePersonRefundDTO $dto);
    public function update(CashStore $cashStore, AccountablePersonRefundDTO $dto);
}
