<?php

namespace App\Repositories\Contracts;

use App\DTO\CounterpartyAgreementDTO;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CounterpartyAgreementRepositoryInterface extends IndexInterface
{
    public function store(CounterpartyAgreementDTO $DTO);

    public function update(CounterpartyAgreement $cpAgreement, CounterpartyAgreementDTO $DTO) :CounterpartyAgreement;

    public function getAgreementByCounterpartyId(Counterparty $counterparty, array $data) :LengthAwarePaginator;
}
