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

    public function creditArticle(): BelongsTo
    {
        return $this->belongsTo(BalanceArticle::class, 'debit_article');
    }

    public function debitArticle(): BelongsTo
    {
        return $this->belongsTo(BalanceArticle::class, 'credit_article');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
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
