<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\InvestmentDTO;
use App\Models\CashStore;

interface InvestmentRepositoryInterface
{
    public function index(array $data);
    public function store(InvestmentDTO $dto);
    public function update(CashStore $cashStore, InvestmentDTO $dto);
}
