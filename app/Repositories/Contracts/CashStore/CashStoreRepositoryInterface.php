<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;

interface CashStoreRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);
}
