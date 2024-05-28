<?php

namespace App\Repositories;

use App\DTO\OrganizationDTO;
use App\DTO\OrganizationUpdateDTO;
use App\Models\Organization;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public const ON_PAGE = 10;

    public $model = Organization::class;

    use Sort, FilterTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['director', 'chiefAccountant']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(OrganizationDTO $DTO)
    {
        return Organization::create([
            'name' => $DTO->name,
            'INN' => $DTO->INN,
            'director_id' => $DTO->director_id,
            'chief_accountant_id' => $DTO->chief_accountant_id,
            'address' => $DTO->address,
            'description' => $DTO->description
        ]);
    }

    public function update(Organization $organization, OrganizationUpdateDTO $DTO): Organization
    {
        $organization->update([
            'name' => $DTO->name,
            'INN' => $DTO->INN,
            'director_id' => $DTO->director_id,
            'chief_accountant_id' => $DTO->chief_accountant_id,
            'address' => $DTO->address,
            'description' => $DTO->description
        ]);

        return $organization;
    }

    public function search(array $filteredParams)
    {
        if (!$filteredParams['search']) {
            return $this->model::query();
        }
        return $this->model::where(function ($query) use ($filteredParams) {
            $query->where('name', 'like', '%' . $filteredParams['search'] . '%')
                ->orWhereHas('chiefAccountant', function ($query) use ($filteredParams) {
                    $query->where('name', 'like', '%' . $filteredParams['search'] . '%');
                })
                ->orWhereHas('director', function ($query) use ($filteredParams) {
                    $query->where('name', 'like', '%' . $filteredParams['search'] . '%');
                });
        });

    }

    public function filter($query, array $data)
    {
        return $query->when($data['chief_accountant_id'], function ($query) use ($data) {
            return $query->where('chief_accountant_id', $data['chief_accountant_id']);
        })
            ->when($data['director_id'], function ($query) use ($data) {
                return $query->where('director_id', $data['director_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['description'], function ($query) use ($data) {
                return $query->where('description', 'like', '%' . $data['description'] . '%');
            })
            ->when($data['INN'], function ($query) use ($data) {
                return $query->where('INN', 'like', '%' . $data['INN'] . '%');
            })
            ->when($data['address'], function ($query) use ($data) {
                return $query->where('address', 'like', '%' . $data['address'] . '%');
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

    public function export(array $data): string
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams);

        if ($filteredParams['deleted']) {
            $query;
        }

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['director', 'chiefAccountant']);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'ИНН', 'Директор', 'Гл. бухгалтер', 'Адрес', 'Описание', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->INN,
                $row->director->name,
                $row->chiefAccountant->name,
                $row->address,
                $row->description,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }
}
