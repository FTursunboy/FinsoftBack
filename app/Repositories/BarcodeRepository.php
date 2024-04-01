<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class BarcodeRepository implements BarcodeRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Barcode::class;

    public function store(BarcodeDTO $DTO) :Barcode
    {
        return Barcode::create([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);
    }

    public function update(Barcode $barcode, BarcodeDTO $DTO) :Barcode
    {
        $barcode->update([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);

        return $barcode;
    }

    public function delete(Barcode $barcode)
    {
        $barcode->delete();
    }

    public function index(Good $good, array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $query->where('good_id', $good->id);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('barcode', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
