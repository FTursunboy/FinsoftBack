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
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class HiringRepository implements HiringRepositoryInterface
{
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
            'basis' => $DTO->basis
        ]);
    }

    public function uniqueNumber(): string
    {
        $lastRecord = Hiring::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
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
            'basis' => $DTO->basis
        ]);
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::filter($filteredParams);

        return $query->with(['employee', 'organization', 'position', 'department'])->paginate($filteredParams['itemsPerPage']);
    }
}
