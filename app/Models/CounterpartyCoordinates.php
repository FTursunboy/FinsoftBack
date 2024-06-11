<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounterpartyCoordinates extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'counterparty_id',
    ];

    protected $casts = [
        'location' => Coordinates::class,
    ];

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }
}
