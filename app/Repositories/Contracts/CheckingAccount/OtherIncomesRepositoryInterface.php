<?php

namespace App\Repositories\Contracts\CheckingAccount;

use App\DTO\CheckingAccount\OtherIncomesDTO;

interface OtherIncomesRepositoryInterface
{
    public function index(array $data);
    public function store(OtherIncomesDTO $dto);
}
