<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\OtherExpensesDTO;

interface OtherExpensesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherExpensesDTO $dto);
    public function balanceArticle(array $data);
}
