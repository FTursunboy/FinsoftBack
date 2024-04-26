<?php

namespace App\Repositories;

use App\DTO\WorkerScheduleDTO;
use App\Models\WorkerSchedule;
use App\Repositories\Contracts\WorkerScheduleRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;

class WorkerScheduleRepository implements WorkerScheduleRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = WorkerSchedule::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['month']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(WorkerScheduleDTO $DTO) :WorkerSchedule
    {
        return $this->model::create([
            'name' => $DTO->name,
            'month_id' => $DTO->month_id,
            'number_of_hours' => $DTO->number_of_hours
        ]);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('name', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
