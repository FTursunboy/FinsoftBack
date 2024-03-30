<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class CounterpartyAgreement extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name','contract_number','date','organization_id','counterparty_id',
        'contact_person','currency_id','payment_id', 'price_type_id', 'comment'];


    public function organization() :BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public static function bootSoftDeletes()
    {

    }

    public function counterparty() :BelongsTo
    {
        return $this->belongsTo(Counterparty::class, 'counterparty_id');
    }

    public function currency() :BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function payment() :BelongsTo
    {
        return $this->belongsTo(Currency::class, 'payment_id');
    }

    public function priceType() :BelongsTo
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'contract_number' => $this->contract_number,
            'date' => $this->date,
            'contact_person' => $this->contact_person,

        ];
    }
}
