<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\ClientPaymentDTO;

interface CashStoreRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);
}
