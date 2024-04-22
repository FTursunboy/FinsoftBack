<?php

namespace App\Repositories\Contracts\CashStore;

use App\DTO\CashStore\OtherIncomesDTO;

interface OtherIncomesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherIncomesDTO $dto);
}
