<?php

namespace App\Repositories\Contracts\CheckingAccount;

interface CheckingAccountRepositoryInterface
{
    public function index(array $data, string $type);

    public function massDelete(array $ids);

    public function approve(array $ids);

    public function unApprove(array $ids);
}
