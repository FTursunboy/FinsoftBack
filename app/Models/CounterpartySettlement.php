<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounterpartySettlement extends Model
{

    protected $fillable = [
        'movement_type',
        'sale_sum',
        'sum',
        'model_id',
        'active',
        'date',
        'counterparty_id',
        'counterparty_agreement_id',
        'organization_id'
    ];

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class, 'counterparty_id');
    }

    public function counterpartyAgreement(): BelongsTo
    {
        return $this->belongsTo(CounterpartyAgreement::class, 'counterparty_agreement_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id')->withTrashed();
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
        ];
    }
}
