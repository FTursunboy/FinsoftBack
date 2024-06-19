<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends DocumentModel
{
    protected $fillable = ['date','doc_number','organization_id','good_id','storage_id','amount', 'author_id', 'sum'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function good(): BelongsTo
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function documentGoods(): HasMany
    {
        return $this->hasMany(EquipmentGoods::class, 'equipment_document_id', 'id');
    }

    public static function filter(array $data): array
    {
        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['organization_id'] ?? null,
            'good_id' => $data['good_id'] ?? null,
            'storage_id' => $data['storage_id'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'startDate' => $data['startDate'] ?? null,
            'endDate' => $data['endDate'] ?? null,
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['storage_id'] = $data['filterData']['storage_id'] ?? $filteredData['storage_id'];
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['good_id'] = $data['filterData']['good_id'] ?? $filteredData['good_id'];
            $filteredData['startDate'] = $data['filterData']['startDate'] ?? $filteredData['startDate'];
            $filteredData['endDate'] = $data['filterData']['endDate'] ?? $filteredData['endDate'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
        }

        return $filteredData;
    }
}
