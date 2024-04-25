<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\CreditReceiveDTO;
use App\Models\CheckingAccount;

interface CreditReceiveRepositoryInterface
{
    public function index(array $data);
    public function store(CreditReceiveDTO $dto);

    public function update(CreditReceiveDTO $dto, CheckingAccount $account) ;
}
