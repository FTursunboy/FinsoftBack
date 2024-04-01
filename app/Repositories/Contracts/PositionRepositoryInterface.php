<?php

namespace App\Repositories\Contracts;

use App\DTO\PositionDTO;
use App\Models\Position;

interface PositionRepositoryInterface extends IndexInterface
{
    public function store(PositionDTO $DTO);

    public function update(Position $position, PositionDTO $DTO) :Position;
}
