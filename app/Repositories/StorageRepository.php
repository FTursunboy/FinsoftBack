<?php

namespace App\Repositories;

use App\DTO\StorageDTO;
use App\DTO\StorageEmployeeDTO;
use App\DTO\StorageEmployeeUpdateDTO;
use App\DTO\StorageUpdateDTO;
use App\Models\EmployeeStorage;
use App\Models\Storage;
use App\Repositories\Contracts\StorageRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StorageRepository implements StorageRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Storage::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['organization', 'group']);

        return $query->paginate($filterParams['itemsPerPage']);
    }


    public function store(StorageDTO $DTO)
    {
        try {
            return DB::transaction(function () use ($DTO) {
                $storage = Storage::create([
                    'name' => $DTO->name,
                    'organization_id' => $DTO->organization_id,
                    'group_id' => $DTO->group_id
                ]);

                if ($DTO->storage_data) EmployeeStorage::insert($this->storageData($DTO->storage_data, $storage));

                return $storage->load('organization');
            });

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Storage $storage, StorageUpdateDTO $DTO): Storage
    {
        $storage->update([
            'name' => $DTO->name,
            'organization_id' => $DTO->organization_id
        ]);

        return $storage;
    }

    public function updateEmployee(EmployeeStorage $employee, StorageEmployeeUpdateDTO $DTO): EmployeeStorage
    {
        $employee->update([
            'employee_id' => $DTO->employee_id,
            'from' => $DTO->from,
            'to' => $DTO->to
        ]);

        return $employee;
    }

    public function addEmployee(Storage $storage, StorageEmployeeDTO $DTO)
    {
        return EmployeeStorage::create([
            'storage_id' => $storage->id,
            'employee_id' => $DTO->employee_id,
            'from' => $DTO->from,
            'to' => $DTO->to
        ]);
    }

    public function storageData(array $storage_data, Storage $storage): array
    {
        return array_map(function ($item) use ($storage) {
            return [
                'storage_id' => $storage->id,
                'employee_id' => $item['employee_id'],
                'from' => $item['from'],
                'to' => $item['to'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }, $storage_data);
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }

    public function export(array $data): string
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, ['organization', 'group']);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'Организация', 'Группа', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->organization?->name,
                $row->group?->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }
}
