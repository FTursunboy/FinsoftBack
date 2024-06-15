<?php

namespace App\Repositories\Contracts\CashStore;


interface CashStoreRepositoryInterface
{
    public function index(array $data, string $type);

    public function approve(array $ids);

    public function unApprove(array $ids);

    public function massDelete(array $ids);
}
