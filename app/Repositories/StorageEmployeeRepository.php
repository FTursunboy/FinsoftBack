<?php

namespace App\Repositories;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageEmployeeUpdateDTO;
use App\DTO\StorageUpdateDTO;
use App\Models\EmployeeStorage;
use App\Models\Storage;
use App\Repositories\Contracts\StorageEmployeeRepositoryInterface;
use App\Repositories\Contracts\StorageRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StorageEmployeeRepository implements StorageEmployeeRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = EmployeeStorage::class;

    public function getEmployeesByStorageId(Storage $storage, array $data)
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search'], $storage);

        $query = $this->sort($filterParams, $query, ['employee']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search, Storage $storage)
    {
        return $this->model::where('storage_id', $storage->id, function ($query) use ($search) {
            return $query->whereAny(['from', 'to'], 'like', '%' . $search . '%')
                ->orWhereHas('employee', function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                });
        });
    }
}
