<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\ProviderRefundDTO;
use App\Models\CashStore;

interface ProviderRefundRepositoryInterface
{
    public function index(array $data);
    public function store(ProviderRefundDTO $dto);
    public function update(CashStore $cashStore, ProviderRefundDTO $dto);
}
