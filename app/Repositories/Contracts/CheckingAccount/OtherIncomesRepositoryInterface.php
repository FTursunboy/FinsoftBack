<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\OtherIncomesDTO;
use App\Models\CheckingAccount;

interface OtherIncomesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherIncomesDTO $dto);

    public function update(OtherIncomesDTO $dto, CheckingAccount $account);
}
