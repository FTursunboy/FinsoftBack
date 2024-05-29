<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\LocationDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Location;
use Illuminate\Pagination\LengthAwarePaginator;

interface LocationRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(LocationDTO $dto) :Location;

    public function update(Location $location, LocationDTO $DTO) :Location;

    public function delete(Location $location);

}
