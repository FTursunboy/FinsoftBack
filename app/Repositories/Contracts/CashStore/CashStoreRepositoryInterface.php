<?php

namespace App\Repositories\Contracts\CashStore;


interface CashStoreRepositoryInterface
{
    public function index(array $data, string $type);
}
