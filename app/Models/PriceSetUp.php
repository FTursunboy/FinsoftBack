<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceSetUp extends Model
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

    public function goodPrices() :HasMany
    {
        return $this->hasMany(SetUpPrice::class);
    }



    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25
        ];
    }
}
