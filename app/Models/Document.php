<?php

namespace App\Models;

use App\Observers\DocumentObserver;
use App\Repositories\Contracts\Documentable;
use App\Repositories\Contracts\SoftDeleteInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;


class Document extends DocumentModel implements SoftDeleteInterface
{
    use SoftDeletes, Searchable, HasFactory;

    protected $fillable = ['doc_number', 'date', 'counterparty_id', 'counterparty_agreement_id', 'organization_id',
            'storage_id', 'author_id', 'active', 'status_id', 'active', 'comment', 'saleInteger', 'salePercent', 'currency_id'];


    protected $casts = [
        'active' => 'bool'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

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
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'currency_id' => $data['filterData']['currency_id'] ?? null,
            'counterparty_id' => $data['filterData']['counterparty_id'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'counterparty_agreement_id' => $data['filterData']['counterparty_agreement_id'] ?? null,
            'storage_id' => $data['filterData']['storage_id'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
        ];
    }
}
