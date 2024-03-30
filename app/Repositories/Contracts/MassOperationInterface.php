<?php

namespace App\Repositories\Contracts;

use App\DTO\LoginDTO;
use App\Models\User;


interface MassOperationInterface
{
    public function massDelete(SoftDeleteInterface $model, array $ids);

    public function massRestore(SoftDeleteInterface $model, array $ids);
}
