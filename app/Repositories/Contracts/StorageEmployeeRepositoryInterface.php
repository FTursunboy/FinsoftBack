<?php

namespace App\Repositories\Contracts;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageEmployeeUpdateDTO;
use App\DTO\StorageUpdateDTO;
use App\Models\EmployeeStorage;
use App\Models\Storage;

interface StorageEmployeeRepositoryInterface
{
    public function getEmployeesByStorageId(Storage $storage, array $data);
}
