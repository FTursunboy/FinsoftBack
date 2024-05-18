<?php

namespace App\Models;

use App\Filters\GoodAccountingFilter;
use App\Filters\MovementDocumentFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class GoodAccounting extends Model
{
    use Filterable;

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

    public function group(): BelongsTo
    {
        return $this->belongsTo(GoodGroup::class, 'group_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(GoodAccountingFilter::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'group_id' => $data['group_id'] ?? null,
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'good_id' => $data['filterData']['good_id'] ?? null,
            'startDate_id' =>  $data['start_date'] ?? null,
            'end_date' =>  $data['filterData']['end_date'] ?? null,
            'date' =>  $data['filterData']['date'] ?? null
        ];
    }
}
