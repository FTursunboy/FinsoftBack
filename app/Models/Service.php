<?php

namespace App\Models;

use App\Enums\ServiceTypes;
use App\Repositories\Contracts\SoftDeleteInterface;
use Google\Service\MyBusinessBusinessInformation\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends DocumentModel implements SoftDeleteInterface
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['doc_number', 'date', 'counterparty_id', 'counterparty_agreement_id', 'organization_id',
        'storage_id', 'author_id', 'active', 'status_id', 'active', 'comment', 'currency_id', 'sales_sum', 'return_sum', 'client_payment', 'deleted_at'];


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

    public function saleGoods(): HasMany
    {
        return $this->hasMany(ServiceGoods::class, 'service_id', 'id')->where('type', ServiceTypes::Sale);
    }

    public function returnGoods(): HasMany {
        return $this->hasMany(ServiceGoods::class, 'service_id', 'id')->where('type', ServiceTypes::Return);
    }

    public function clientPayment()
    {
        return $this->hasOne(CashStore::class);
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
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
            'order_status_id' => $data['order_status_id'] ?? null,
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
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
            $filteredData['order_status_id'] = $data['filterData']['order_status_id'] ?? $filteredData['order_status_id'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }


        return $filteredData;
    }


}
