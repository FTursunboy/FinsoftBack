<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Models\CashStore;

interface ClientPaymentRepositoryInterface
{
    public function index(array $data);
    public function clientPayment(ClientPaymentDTO $dto);
    public function update(CashStore $cashStore, ClientPaymentDTO $dto);
    public function approve(array $data);
}
