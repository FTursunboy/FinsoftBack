<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\EmployeeMovementDTO;
use App\DTO\FiringDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\EmployeeMovement;
use App\Models\Firing;
use App\Models\Good;
use Illuminate\Pagination\LengthAwarePaginator;

interface FiringRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(FiringDTO $dto) :Firing;

    public function update(Firing $employeeMovement, FiringDTO $DTO) :Firing;


}
