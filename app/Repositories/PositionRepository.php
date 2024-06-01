<?php

namespace App\Repositories;

use App\DTO\PositionDTO;
use App\Models\Position;
use App\Repositories\Contracts\PositionRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionRepository implements PositionRepositoryInterface
{
    use FilterTrait, Sort;

    public $model = Position::class;

    public function store(PositionDTO $DTO)
    {
        return $this->model::create([
            'name' => $DTO->name,
        ]);
    }

    public function update(Position $position, PositionDTO $DTO): Position
    {
        $position->update([
            'name' => $DTO->name,
        ]);

        return $position;
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }

    public function filter($query, array $data)
    {
        return $query->when($data['name'], function ($query) use ($data) {
            return $query->where('name', 'like', '%' . $data['name'] . '%');
        })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
            });
    }

    public function export(array $data): string
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, []);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Наименование', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }

}
