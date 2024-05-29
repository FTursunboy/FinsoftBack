<?php

namespace App\Repositories;

use App\DTO\LocationDTO;
use App\Models\Location;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class LocationRepository implements LocationRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Location::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(LocationDTO $DTO) :Location
    {
        return Location::create([
            'name' => $DTO->name
        ]);
    }

    public function update(Location $location, LocationDTO $DTO) :Location
    {
        $location->update([
            'name' => $DTO->name
        ]);

        return $location;
    }

    public function delete(Location $location)
    {
        $location->delete();
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('name', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
