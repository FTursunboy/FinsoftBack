<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\ProviderRefundDTO;

interface ProviderRefundRepositoryInterface
{
    public function index(array $data);
    public function store(ProviderRefundDTO $dto);
}
