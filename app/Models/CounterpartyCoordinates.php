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
        'created_at'
    ];

    protected $casts = [
        'location' => Coordinates::class,
    ];

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    public static function filterData(array $data): array
    {
        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['name'] = $data['filterData']['name'] ?? null;
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }


}
