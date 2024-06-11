<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Coordinates;
use App\Models\CounterpartyCoordinates;
use App\Models\Good;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class CounterpartyCoordinatesRepository
{

    public $model = CounterpartyCoordinates::class;

    public function store(array $data)
    {
        $location = $data['location'];

        $coordinate = new CounterpartyCoordinates($data);

        $coordinate->counterparty_id = \Auth::id();
        $coordinate->location = new Coordinates($location['lat'], $location['lon']);


        $coordinate->save();

        return $coordinate;
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

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filterData($data);
        $query = $this->model::query()->where('counterparty_id', \Auth::id());

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('barcode', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
