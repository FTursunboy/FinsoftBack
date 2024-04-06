<?php

namespace App\Models;

use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

#[ObservedBy([DocumentObserver::class])]
class Document extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use SoftDeletes, Searchable, HasFactory;

    protected $fillable = ['doc_number', 'date', 'counterparty_id', 'counterparty_agreement_id', 'organization_id',
            'storage_id', 'author_id', 'active', 'status_id', 'active', 'comment', 'saleInteger', 'salePercent', 'currency_id'];

    protected $keyType = 'string';

    public $incrementing = false;

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

    public function counterparty_agreement(): BelongsTo
    {
        return $this->belongsTo(CounterpartyAgreement::class, 'counterparty_agreement_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(DocumentHistory::class)->orderBy('created_at');
    }
}
