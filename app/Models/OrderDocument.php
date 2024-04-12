<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class OrderDocument extends Model
{
    protected $fillable = ['doc_number','date','counterparty_id','counterparty_agreement_id','organization_id','shipping_date',
                'order_status_id','author_id','comment','currency_id','summa',];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
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
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'currency_id' => $data['filterData']['currency_id'] ?? null,
            'counterparty_id' => $data['filterData']['counterparty_id'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'counterparty_agreement_id' => $data['filterData']['counterparty_agreement_id'] ?? null,
            'order_status_id' => $data['filterData']['order_status_id'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
        ];
    }
}
