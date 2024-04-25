<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\ClientPaymentDTO;
use App\Models\CheckingAccount;

interface CashStoreRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);

    public function update(ClientPaymentDTO $dto, CheckingAccount $account);
}
