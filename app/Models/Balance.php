<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sum',
        'model_id',
        'active',
        'credit_article',
        'organization_id',
        'debit_article',
        'date'
    ];

    protected function creditArticle(): BelongsTo
    {
        return $this->belongsTo(BalanceArticle::class, 'debit_article');
    }

    protected function debitArticle(): BelongsTo
    {
        return $this->belongsTo(BalanceArticle::class, 'credit_article');
    }

    protected function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
