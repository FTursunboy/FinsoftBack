<?php

namespace App\Repositories\Contracts;

use App\DTO\GoodDTO;
use App\DTO\GoodGroupDTO;
use App\DTO\GoodUpdateDTO;
use App\Models\Good;
use App\Models\GoodGroup;
use Illuminate\Support\Collection;

interface GoodGroupRepositoryInterface extends IndexInterface
{
    public function store(GoodGroupDTO $DTO);

    public function getGoods(GoodGroup $goodGroup, array $data);
}
