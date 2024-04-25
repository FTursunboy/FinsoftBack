<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\OtherIncomesDTO;
use App\Models\CashStore;

interface OtherIncomesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherIncomesDTO $dto);
    public function update(CashStore $cashStore, OtherIncomesDTO $dto);
}
