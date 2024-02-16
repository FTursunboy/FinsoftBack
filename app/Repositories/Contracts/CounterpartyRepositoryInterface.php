<?php

namespace App\Repositories\Contracts;

use App\DTO\CounterpartyDTO;
use App\DTO\PriceTypeDTO;
use App\Models\Counterparty;
use App\Models\PriceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use SebastianBergmann\LinesOfCode\Counter;

interface CounterpartyRepositoryInterface extends SearchInterface {

    public function index() ;

    public function store(CounterpartyDTO $DTO);

    public function update(Counterparty $counterparty, CounterpartyDTO $DTO) :Counterparty;
}
