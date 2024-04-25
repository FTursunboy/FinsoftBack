<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\InvestmentDTO;
use App\Models\CheckingAccount;

interface InvestmentRepositoryInterface
{
    public function index(array $data);
    public function store(InvestmentDTO $dto);


    public function update(InvestmentDTO $dto, CheckingAccount $account);
}
