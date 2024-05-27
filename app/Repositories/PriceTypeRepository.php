<?php

namespace App\Repositories;

use App\DTO\PriceTypeDTO;
use App\Models\PriceType;
use App\Repositories\Contracts\PriceTypeRepository as PriceTypeRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class PriceTypeRepository implements PriceTypeRepositoryInterface
{
    const ON_PAGE = 10;

    use Sort, FilterTrait;

    public $model = PriceType::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['currency']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(PriceTypeDTO $DTO)
    {
        $this->model::create([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'description' => $DTO->description
        ]);
    }

    public function update(PriceType $priceType, PriceTypeDTO $DTO): PriceType
    {
        $priceType->update([
            'name' => $DTO->name,
            'currency_id' => $DTO->currency_id,
            'description' => $DTO->description
        ]);

        return $priceType->load('currency');
    }

    public function search(string $search)
    {
        return $this->model::OrWhere(function ($query) use ($search) {
            return $query->whereAny(['price_types.name', 'price_types.description'], 'like', '%' . $search . '%')
                ->OrWhereHas('currency', function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['currency_id'], function ($query) use ($data) {
            return $query->where('currency_id', $data['currency_id']);
        })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['description'], function ($query) use ($data) {
                return $query->where('description', 'like', '%' . $data['description'] . '%');
            });
    }

    public function export(array $data): string
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['currency']);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'Валюта', 'Описание', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->currency_id,
                $row->description,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }

}
