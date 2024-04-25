<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\ProviderRefundDTO;
use App\Models\CheckingAccount;

interface ProviderRefundRepositoryInterface
{
    public function index(array $data);

    public function store(ProviderRefundDTO $dto);

    public  function update(ProviderRefundDTO $DTO, CheckingAccount $account);
}
