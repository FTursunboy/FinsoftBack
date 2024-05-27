<?php

namespace App\Repositories\Contracts;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageEmployeeUpdateDTO;
use App\DTO\StorageUpdateDTO;
use App\Models\EmployeeStorage;
use App\Models\Storage;

interface StorageRepositoryInterface extends IndexInterface
{
    public function store(StorageDTO $DTO);

    public function update(Storage $storage, StorageUpdateDTO $DTO) :Storage;

    public function updateEmployee(EmployeeStorage $employee, StorageEmployeeUpdateDTO $DTO) :EmployeeStorage;

    public function addEmployee(Storage $storage, StorageEmployeeDTO $DTO);

    public function export(array $data);
}
