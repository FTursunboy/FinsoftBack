<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\InvestmentDTO;

interface InvestmentRepositoryInterface
{
    public function index(array $data);
    public function store(InvestmentDTO $dto);
}
