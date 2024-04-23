<?php

namespace App\Repositories;

use App\DTO\EmployeeDTO;
use App\DTO\EmployeeMovementDTO;
use App\DTO\EmployeeUpdateDTO;
use App\DTO\FiringDTO;
use App\Models\Employee;

use App\Models\EmployeeMovement;
use App\Models\Firing;
use App\Repositories\Contracts\EmployeeMovementRepositoryInterface;
use App\Repositories\Contracts\FiringRepositoryInterface;
use App\Traits\DocNumberTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class FiringRepository implements FiringRepositoryInterface
{
    use DocNumberTrait;

    public $model = Firing::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);

        $query = $this->model::filter($filterParams);

        return $query->with(['employee', 'organization'])->paginate($filterParams['itemsPerPage']);
    }


    public function store(FiringDTO $dto): Firing
    {
        $firing = new Firing();
        $firing->date = $dto->date;
        $firing->employee_id = $dto->employee_id;
        $firing->organization_id = $dto->organization_id;
        $firing->firing_date = $dto->firing_date;
        $firing->basis = $dto->basis;
        $firing->comment = $dto->comment;
        $firing->doc_number = $this->uniqueNumber();
        $firing->author_id  = \Auth::id();
        $firing->save();

        return $firing;
    }

    public function update(Firing $firing, FiringDTO $dto): Firing
    {
        $firing->date = $dto->date;
        $firing->employee_id = $dto->employee_id;
        $firing->organization_id = $dto->organization_id;
        $firing->firing_date = $dto->firing_date;
        $firing->basis = $dto->basis;
        $firing->comment = $dto->comment;
        $firing->save();

        return $firing;
    }
}
