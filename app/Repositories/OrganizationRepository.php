<?php

namespace App\Repositories;

use App\DTO\OrganizationDTO;
use App\Models\Organization;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\ValidFields;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public const ON_PAGE = 10;

    public $model = Organization::class;

    use ValidFields, FilterTrait;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->processSearchData($data);

        $query = $this->model::search($filteredParams['search']);

        if (!is_null($filteredParams['orderBy']) && $this->isValidField($filteredParams['orderBy'])) {
            $query->orderBy($filteredParams['orderBy'], $filteredParams['direction']);
        }

        return $query->paginate($filteredParams['itemsPerPage']);
    }


    public function store(OrganizationDTO $DTO)
    {
        return Organization::create([
            'name' => $DTO->name,
        ]);
    }

    public function update(Organization $organization, OrganizationDTO $DTO) :Organization
    {
        $organization->update([
            'name' => $DTO->name,
        ]);

        return $organization;
    }


}
