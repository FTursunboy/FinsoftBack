<?php

namespace App\Repositories\Contracts;

use App\DTO\CashRegisterDTO;
use App\Models\CashRegister;
use Illuminate\Support\Collection;

interface CashRegisterRepositoryInterface extends IndexInterface
{
    public function store(CashRegisterDTO $DTO);

    public function update(CashRegister $cashRegister, CashRegisterDTO $DTO) :CashRegister;

    public function export(array $data);

}
