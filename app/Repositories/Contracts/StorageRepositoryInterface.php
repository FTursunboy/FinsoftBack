<?php

namespace App\Repositories\Contracts;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageUpdateDTO;
use App\Models\Storage;

interface StorageRepositoryInterface extends IndexInterface
{
    public function store(StorageDTO $DTO);

    public function update(Storage $storage, StorageUpdateDTO $DTO) :Storage;

    public function addEmployee(Storage $storage, StorageEmployeeDTO $DTO);

    public function getEmployeesByStorageId(Storage $storage, array $data);
}
