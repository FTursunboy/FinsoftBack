<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\AccountablePersonRefundDTO;

interface AccountablePersonRefundRepositoryInterface
{
    public function index(array $data);
    public function store(AccountablePersonRefundDTO $dto);
}
