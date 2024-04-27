<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\HiringDTO;
use App\Models\Barcode;
use App\Models\Document;
use App\Models\Good;
use App\Models\Hiring;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\HiringRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class HiringRepository implements HiringRepositoryInterface
{
    use DocNumberTrait;

    public $model = Hiring::class;

    public function store(HiringDTO $DTO)
    {
       return $this->model::create([
            'doc_number' => $this->uniqueNumber(),
            'organization_id' => $DTO->organization_id,
            'position_id' => $DTO->position_id,
            'department_id' => $DTO->department_id,
            'data' => $DTO->date,
            'hiring_date' => $DTO->hiring_date,
            'employee_id' => $DTO->employee_id,
            'salary' => $DTO->salary,
            'basis' => $DTO->basis,
           'schedule_id' => $DTO->schedule_id,
           'comment' => $DTO->comment,
           'author_id' => \Auth::id()
        ]);
    }



    public function update(Hiring $hiring, HiringDTO $DTO)
    {
        $hiring->update([
            'organization_id' => $DTO->organization_id,
            'position_id' => $DTO->position_id,
            'department_id' => $DTO->department_id,
            'data' => $DTO->date,
            'hiring_date' => $DTO->hiring_date,
            'employee_id' => $DTO->employee_id,
            'salary' => $DTO->salary,
            'basis' => $DTO->basis,
            'comment' => $DTO->comment,
            'author_id' => \Auth::id(),
        ]);
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['employee', 'organization', 'position', 'department', 'author'])->paginate($filteredParams['itemsPerPage']);
    }
}
