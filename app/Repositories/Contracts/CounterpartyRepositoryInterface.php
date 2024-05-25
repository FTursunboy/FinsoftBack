<?php

namespace App\Repositories\Contracts;

use App\DTO\CounterpartyDTO;
use App\DTO\PriceTypeDTO;
use App\Models\Counterparty;
use App\Models\PriceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use SebastianBergmann\LinesOfCode\Counter;

interface CounterpartyRepositoryInterface extends IndexInterface
{
    public function store(CounterpartyDTO $DTO);

    public function update(Counterparty $counterparty, CounterpartyDTO $DTO) :Counterparty;

    public function delete(Counterparty $counterparty);

    public function massDelete(array $ids);

    public function providers(array $data);

    public function clients(array $data);

    public function export(array $data);
}
