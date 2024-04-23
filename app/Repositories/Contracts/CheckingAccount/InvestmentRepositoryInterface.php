<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\InvestmentDTO;

interface InvestmentRepositoryInterface
{
    public function index(array $data);
    public function store(InvestmentDTO $dto);
}
