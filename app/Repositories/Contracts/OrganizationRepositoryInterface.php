<?php

namespace App\Repositories\Contracts;

use App\DTO\OrganizationDTO;
use App\DTO\OrganizationUpdateDTO;
use App\Http\Requests\Api\IndexRequest;
use App\Models\Organization;
use Cassandra\Index;
use Illuminate\Support\Collection;

interface OrganizationRepositoryInterface extends IndexInterface
{
    public function store(OrganizationDTO $DTO);

    public function update(Organization $organization, OrganizationUpdateDTO $DTO) :Organization;
}
