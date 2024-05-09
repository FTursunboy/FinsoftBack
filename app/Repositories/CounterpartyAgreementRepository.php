<?php

namespace App\Repositories;

use App\DTO\CounterpartyAgreementDTO;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Repositories\Contracts\CounterpartyAgreementRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CounterpartyAgreementRepository implements CounterpartyAgreementRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = CounterpartyAgreement::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->processSearchData($data);

        $query = $this->search($filteredParams['search']);

        $query = $this->sort($filteredParams, $query, ['organization', 'counterparty', 'currency', 'payment', 'priceType']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(CounterpartyAgreementDTO $DTO)
    {
        CounterpartyAgreement::create([
            'name' => $DTO->name,
            'contract_number' => $this->contractNumber(),
            'date' => Carbon::now(),
            'organization_id' => $DTO->organization_id,
            'counterparty_id' => $DTO->counterparty_id,
            'contact_person' => $DTO->contact_person,
            'currency_id' => $DTO->currency_id,
            'payment_id' => $DTO->payment_id,
            'comment' => $DTO->comment,
            'price_type_id' => $DTO->price_type_id,
        ]);
    }

    public function update(CounterpartyAgreement $counterpartyAgreement, CounterpartyAgreementDTO $DTO): CounterpartyAgreement
    {
        $counterpartyAgreement->update([
            'name' => $DTO->name,
            'date' => Carbon::now(),
            'organization_id' => $DTO->organization_id,
            'counterparty_id' => $DTO->counterparty_id,
            'contact_person' => $DTO->contact_person,
            'currency_id' => $DTO->currency_id,
            'payment_id' => $DTO->payment_id,
            'comment' => $DTO->comment,
            'price_type_id' => $DTO->price_type_id,
        ]);

        return $counterpartyAgreement->load(['organization', 'counterparty', 'currency', 'payment', 'priceType']);
    }

    public function getAgreementByCounterpartyId(Counterparty $counterparty, array $data): LengthAwarePaginator
    {
        $filteredParams = $this->processSearchData($data);

        $query = $this->model::search($filteredParams['search'])->query(function ($query) use ($counterparty) {
            $query->where('counterparty_id', $counterparty->id)->with(['organization', 'counterparty', 'currency', 'payment', 'priceType']);
        });

        if (!is_null($filteredParams['sort'])) {
            $query->orderBy($filteredParams['sort'], $filteredParams['direction']);
        }

        return $query->paginate($filteredParams['itemsPerPage']);
    }


    public function contractNumber(): string
    {
        $lastRecord = CounterpartyAgreement::query()->latest()->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->contract_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }
}
