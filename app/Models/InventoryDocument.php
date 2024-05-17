<?php

namespace App\Models;

use App\Filters\InventoryDocumentFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class InventoryDocument extends DocumentModel implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Filterable, SoftDeletes;

    protected $fillable = ['doc_number', 'date', 'organization_id', 'storage_id', 'responsible_person_id', 'author_id', 'comment', 'deleted_at'];

    protected $casts = [
        'active' => 'bool'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id');
    }

    public function inventoryDocumentGoods(): HasMany
    {
        return $this->hasMany(InventoryDocumentGoods::class, 'inventory_document_id', 'id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(InventoryDocumentFilter::class);
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'storage_id' => $data['filterData']['storage_id'] ?? null,
            'responsible_person_id' =>  $data['filterData']['responsible_person_id'] ?? null,
            'author_id' =>  $data['filterData']['author_id'] ?? null,
            'startDate' => $data['filterData']['startDate'] ?? null,
            'endDate' => $data['filterData']['endDate'] ?? null,
            'active' => $data['filterData']['active'] ?? null,
            'deleted' => $data['filterData']['deleted'] ?? null,
        ];
    }
}
