<?php

namespace App\Models;

use App\Repositories\Contracts\SoftDeleteInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;


class Document extends DocumentModel implements SoftDeleteInterface
{
    use Searchable, HasFactory;

    protected $fillable = ['doc_number', 'date', 'counterparty_id', 'counterparty_agreement_id', 'organization_id',
            'storage_id', 'author_id', 'active', 'status_id', 'active', 'comment', 'saleInteger', 'salePercent', 'currency_id', 'sale_sum', 'sum', 'deleted_at'];


    protected $casts = [
        'active' => 'bool'
    ];


    public static function bootSoftDeletes()
    {

    }



    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class, 'counterparty_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function counterpartyAgreement(): BelongsTo
    {
        return $this->belongsTo(CounterpartyAgreement::class, 'counterparty_agreement_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(DocumentHistory::class)->orderBy('created_at');
    }

    public function documentGoods(): HasMany
    {
        return $this->hasMany(GoodDocument::class, 'document_id', 'id');
    }

    public static function filter(array $data): array
    {
        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'currency_id' => $data['currency_id'] ?? null,
            'counterparty_id' => $data['counterparty_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'counterparty_agreement_id' => $data['counterparty_agreement_id'] ?? null,
            'storage_id' => $data['storage_id'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'startDate' => $data['startDate'] ?? null,
            'endDate' => $data['endDate'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['storage_id'] = $data['filterData']['storage_id'] ?? $filteredData['storage_id'];
            $filteredData['currency_id'] = $data['filterData']['currency_id'] ?? $filteredData['currency_id'];
            $filteredData['counterparty_id'] = $data['filterData']['counterparty_id'] ?? $filteredData['counterparty_id'];
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['counterparty_agreement_id'] = $data['filterData']['counterparty_agreement_id'] ?? $filteredData['counterparty_agreement_id'];
            $filteredData['startDate'] = $data['filterData']['startDate'] ?? $filteredData['startDate'];
            $filteredData['endDate'] = $data['filterData']['endDate'] ?? $filteredData['endDate'];
        }


        return $filteredData;
    }

    public function totalGoodsSum() :HasMany
    {
        return $this->hasMany(GoodDocument::class)
            ->selectRaw('document_id, SUM(price * amount) as total_sum')
            ->groupBy('document_id');
    }

    public function documentGoodsWithCount() :HasMany
    {
        return $this->hasMany(GoodDocument::class)
            ->selectRaw('document_id, COUNT(*) as total_count')
            ->groupBy('document_id');
    }

    public function goodAccountents()
    {
        return $this->hasMany(GoodAccounting::class, 'model_id');
    }

    public function counterpartySettlements(): HasMany
    {
        return $this->hasMany(CounterpartySettlement::class, 'model_id');
    }

    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class, 'model_id');
    }
}
