<?php

namespace App\Models;

use App\Repositories\Contracts\Document\Documentable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class OrderDocument extends DocumentModel  implements Documentable, \App\Repositories\Contracts\SoftDeleteInterface
{
    protected $fillable = ['doc_number','date','counterparty_id','counterparty_agreement_id','organization_id','shipping_date',
                'order_status_id','author_id','comment','currency_id','summa', 'order_type_id', 'active', 'deleted_at'];


    protected $casts = [
        'active' => 'boolean'
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

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function orderDocumentGoods(): HasMany
    {
        return $this->hasMany(OrderDocumentGoods::class, 'order_document_id', 'id');
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
            'order_status_id' => $data['order_status_id'] ?? null,
            'startDate' => $data['startDate'] ?? null,
            'endDate' => $data['endDate'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['counterparty_id'] = $data['filterData']['counterparty_id'] ?? $filteredData['counterparty_id'];
            $filteredData['currency_id'] = $data['filterData']['currency_id'] ?? $filteredData['currency_id'];
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['counterparty_agreement_id'] = $data['filterData']['counterparty_agreement_id'] ?? $filteredData['counterparty_agreement_id'];
            $filteredData['order_status_id'] = $data['filterData']['order_status_id'] ?? $filteredData['order_status_id'];
            $filteredData['startDate'] = $data['filterData']['startDate'] ?? $filteredData['startDate'];
            $filteredData['endDate'] = $data['filterData']['endDate'] ?? $filteredData['endDate'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }

    public function documentGoodsWithCount() :HasMany
    {
        return $this->hasMany(OrderDocumentGoods::class)
            ->selectRaw('order_document_id, COUNT(*) as total_count')
            ->groupBy('order_document_id');
    }
}
