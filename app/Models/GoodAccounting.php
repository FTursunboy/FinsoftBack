<?php

namespace App\Models;

use App\Filters\GoodAccountingFilter;
use App\Filters\MovementDocumentFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodAccounting extends Model
{
    use SoftDeletes, Filterable;

    protected $fillable = [
        'movement_type',
        'amount',
        'sum',
        'model_id',
        'active',
        'date'
    ];

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(GoodAccountingFilter::class);
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'good_id' => $data['filterData']['good_id'] ?? null
            ];
    }
}
