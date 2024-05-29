<?php

namespace App\Repositories;

use App\DTO\CashRegisterDTO;
use App\Models\CashRegister;
use App\Repositories\Contracts\CashRegisterRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class CashRegisterRepository implements CashRegisterRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = CashRegister::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['organization', 'currency', 'responsiblePerson']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(CashRegisterDTO $DTO)
    {
        $this->model::create([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'organization_id' => $DTO->organization_id,
            'responsible_person_id' => $DTO->responsible_person_id
        ]);
    }

    public function update(CashRegister $cashRegister, CashRegisterDTO $DTO): CashRegister
    {
        $cashRegister->update([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'organization_id' => $DTO->organization_id,
            'responsible_person_id' => $DTO->responsible_person_id
        ]);

        return $cashRegister->load(['currency', 'organization', 'responsiblePerson']);
    }

    public function search(array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $this->model::where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('currency', function ($query) use ($searchTerm) {
                    return $query->where('currencies.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('responsiblePerson', function ($query) use ($searchTerm) {
                    return $query->where('employees.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['responsible_person_id'], function ($query) use ($data) {
                return $query->where('responsible_person_id', $data['responsible_person_id']);
            })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization_id', $data['organization_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

    public function export(array $data): string
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['organization', 'currency', 'responsiblePerson']);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'Валюта', 'Организация', 'Ответственное лицо', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->currency?->name,
                $row->organization?->name,
                $row->responsiblePerson?->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }
}
