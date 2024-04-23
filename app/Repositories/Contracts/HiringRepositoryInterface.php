<?php

namespace App\Repositories\Contracts;

use App\DTO\HiringDTO;
use App\Models\Hiring;
interface HiringRepositoryInterface extends IndexInterface
{
    public function store(HiringDTO $DTO);

    public function update(Hiring $hiring, HiringDTO $DTO);
}
