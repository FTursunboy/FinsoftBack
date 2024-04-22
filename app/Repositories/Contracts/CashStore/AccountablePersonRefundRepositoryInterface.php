<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\AccountablePersonRefundDTO;

interface AccountablePersonRefundRepositoryInterface
{
    public function index(array $data);
    public function store(AccountablePersonRefundDTO $dto);
}
