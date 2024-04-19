<?php

namespace App\Repositories\Contracts;

use App\DTO\CounterpartyDTO;
use App\DTO\PriceTypeDTO;
use App\Models\Counterparty;
use App\Models\PriceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use SebastianBergmann\LinesOfCode\Counter;

interface CounterpartyRepositoryInterface extends IndexInterface
{
    public function store(CounterpartyDTO $DTO);

    public function update(Counterparty $counterparty, CounterpartyDTO $DTO) :Counterparty;

    public function delete(Counterparty $counterparty);

    public function massDelete(array $ids);

    public function getCounterpartyByRole(array $data, string $role) :LengthAwarePaginator;
}
