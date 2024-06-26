<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceSetUp extends DocumentModel
{
    use SoftDeletes;

    protected $fillable = [
        'doc_number',
        'start_date',
        'organization_id',
        'author_id',
        'comment',
        'basis',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function setupGoods(): HasMany
    {
        return $this->hasMany(SetUpPrice::class, 'price_set_up_id', 'id');
    }

    public static function filter(array $data): array
    {
        $filteredData = [
            'sort' => $data['orderBy'] ?? null,
            'search' => $data['search'] ?? '',
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'deleted' => $data['deleted'] ?? null,
            'organization' => $data['organization'] ?? null
        ];

        if (isset($data['filterData'])) {
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }
}
