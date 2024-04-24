<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;

interface ClientPaymentRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);
}
