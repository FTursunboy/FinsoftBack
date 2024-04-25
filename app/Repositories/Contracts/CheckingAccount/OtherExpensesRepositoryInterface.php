<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\OtherExpensesDTO;
use App\Models\CheckingAccount;

interface OtherExpensesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherExpensesDTO $dto);

    public function update(OtherExpensesDTO $dto, CheckingAccount $account);

    public function balanceArticle(array $data);
}
