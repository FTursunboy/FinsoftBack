<?php

namespace App\Repositories;

use App\DTO\CashRegisterDTO;
use App\DTO\EmployeeDTO;
use App\DTO\EmployeeUpdateDTO;
use App\Models\CashRegister;
use App\Models\Employee;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Employee::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['position']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(EmployeeDTO $DTO)
    {
        $image = $DTO->image ? Storage::disk('public')->put('employeePhoto', $DTO->image) : null;

        return Employee::create([
            'name' => $DTO->name,
            'image' => $image,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
            'address' => $DTO->address,
            'group_id' => $DTO->group_id
        ]);
    }

    public function update(Employee $employee, EmployeeUpdateDTO $DTO): Employee
    {
        if ($DTO->image != null) {
            $image = Storage::disk('public')->put('employeePhoto', $DTO->image);
            Storage::delete('public/' . $employee->image);
        }

        $employee->update([
            'name' => $DTO->name,
            'image' => $image ?? $employee->image,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
            'address' => $DTO->address,
            'group_id' => $DTO->group_id
        ]);

        return $employee;
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }
}
