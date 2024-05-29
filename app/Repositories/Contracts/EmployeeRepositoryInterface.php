<?php

namespace App\Repositories\Contracts;

use App\DTO\CashRegisterDTO;
use App\DTO\EmployeeDTO;
use App\DTO\EmployeeUpdateDTO;
use App\Models\CashRegister;
use App\Models\Employee;
use Illuminate\Support\Collection;

interface EmployeeRepositoryInterface extends IndexInterface
{

    public function store(EmployeeDTO $DTO);

    public function update(Employee $employee, EmployeeUpdateDTO $DTO) :Employee;

    public function deleteImage(Employee $employee);

    public function export(array $data);
}
