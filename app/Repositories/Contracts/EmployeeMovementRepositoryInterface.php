<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\EmployeeMovementDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\EmployeeMovement;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeMovementRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(EmployeeMovementDTO $dto) :EmployeeMovement;

    public function update(EmployeeMovement $employeeMovement, EmployeeMovementDTO $DTO) :EmployeeMovement;


}
