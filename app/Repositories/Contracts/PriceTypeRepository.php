<?php

namespace App\Repositories\Contracts;

use App\DTO\PriceTypeDTO;
use App\Http\Requests\Api\IndexRequest;
use App\Models\PriceType;
use Illuminate\Http\JsonResponse;

interface PriceTypeRepository extends IndexInterface
{
    public function store(PriceTypeDTO $DTO);

    public function update(PriceType $priceType, PriceTypeDTO $DTO) :PriceType;

    public function export(array $data);
}
