<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\ImageDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodImages;
use http\Params;
use Illuminate\Pagination\LengthAwarePaginator;

interface ImageRepositoryInterface
{
    public function index(Good $good, array $data);

    public function store(ImageDTO $dto);

    public function delete(GoodImages $images);

}
