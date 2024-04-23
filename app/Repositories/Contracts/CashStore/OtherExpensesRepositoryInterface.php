<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\OtherExpensesDTO;

interface OtherExpensesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherExpensesDTO $dto);
    public function balanceArticle(array $data);
}
