<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\AccountablePersonRefundDTO;
use App\Models\CheckingAccount;

interface AccountablePersonRefundRepositoryInterface
{
    public function index(array $data);

    public function store(AccountablePersonRefundDTO $dto);

    public function update(AccountablePersonRefundDTO $dto, CheckingAccount $account);


}
