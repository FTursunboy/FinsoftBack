<?php

namespace App\Repositories\Contracts;

use App\DTO\PriceSetUpDTO;
use App\Models\PriceSetUp;

interface PriceSetUpRepositoryInterface extends IndexInterface
{
    public function store(PriceSetUpDTO $DTO);

    public function update(PriceSetUp $priceType, PriceSetUpDTO $DTO);

}
