<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\DTO\ImageDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\GoodImages;
use Illuminate\Pagination\LengthAwarePaginator;

interface ImageRepositoryInterface
{
    public function index(Good $good, array $data) :LengthAwarePaginator;

    public function store(ImageDTO $dto);

    public function update(GoodImages $barcode, ImageDTO $DTO);

    public function delete(GoodImages $images);

}
