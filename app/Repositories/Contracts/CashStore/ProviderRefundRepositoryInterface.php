<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\ProviderRefundDTO;

interface ProviderRefundRepositoryInterface
{
    public function index(array $data);
    public function store(ProviderRefundDTO $dto);
}
