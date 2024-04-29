<?php

namespace App\Repositories;

use App\DTO\EmployeeDTO;
use App\DTO\EmployeeMovementDTO;
use App\DTO\EmployeeUpdateDTO;
use App\Models\Employee;

use App\Models\EmployeeMovement;
use App\Repositories\Contracts\EmployeeMovementRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class EmployeeMovementRepository implements EmployeeMovementRepositoryInterface
{
    use DocNumberTrait;

    public $model = EmployeeMovement::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::filter($filterParams);

        return $query->with(['department', 'position', 'employee', 'organization'])->paginate($filterParams['itemsPerPage']);
    }

    public function store(EmployeeMovementDTO $DTO) :EmployeeMovement
    {
        return EmployeeMovement::create([
            'employee_id' => $DTO->employee_id,
            'position_id' => $DTO->position_id,
            'department_id' => $DTO->department_id,
            'organization_id' => $DTO->organization_id,
            'basis' => $DTO->basis,
            'date' => $DTO->date,
            'movement_date' => $DTO->movement_date,
            'salary' => $DTO->salary,
            'doc_number' => $this->uniqueNumber(),
            'comment' => $DTO->comment,
            'author_id' => \Auth::id(),
            'schedule_id' => $DTO->schedule_id
        ]);
    }

    public function update(EmployeeMovement $employeeMovement, EmployeeMovementDTO $DTO): EmployeeMovement
    {
        $employeeMovement->update([
            'employee_id' => $DTO->employee_id,
            'position_id' => $DTO->position_id,
            'department_id' => $DTO->department_id,
            'organization_id' => $DTO->organization_id,
            'basis' => $DTO->basis,
            'date' => $DTO->date,
            'movement_date' => $DTO->movement_date,
            'salary' => $DTO->salary,
            'doc_number' => $this->uniqueNumber(),
            'comment' => $DTO->comment,
            'author_id' => \Auth::id()
        ]);

        return $employeeMovement;
    }


}
