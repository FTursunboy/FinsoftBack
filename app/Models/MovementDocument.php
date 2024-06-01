<?php

namespace App\Models;

use App\Filters\MovementDocumentFilter;
use App\Observers\MovementDocumentObserver;
use App\Repositories\Contracts\Document\Documentable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


#[ObservedBy([MovementDocumentObserver::class])]
class MovementDocument extends DocumentModel implements Documentable
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'uuid',
        'doc_number',
        'date',
        'organization_id',
        'sender_storage_id',
        'recipient_storage_id',
        'author_id',
        'comment',
        'active'
    ];

    public static function bootSoftDeletes(){}

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sender_storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'sender_storage_id');
    }

    public function recipient_storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'recipient_storage_id');
    }

    public function goods(): HasMany
    {
        return $this->hasMany(GoodDocument::class, 'document_id', 'id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function documentGoods(): HasMany
    {
        return $this->hasMany(GoodDocument::class, 'document_id', 'id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(MovementDocumentFilter::class);
    }

    public static function filterData(array $data): array
    {
        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'recipient_storage_id' => $data['recipient_storage_id'] ?? null,
            'sender_storage_id' => $data['sender_storage_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'startDate' => $data['startDate'] ?? null,
            'endDate' => $data['endDate'] ?? null,
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['recipient_storage_id'] = $data['filterData']['recipient_storage_id'] ?? $filteredData['recipient_storage_id'];
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['sender_storage_id'] = $data['filterData']['sender_storage_id'] ?? $filteredData['sender_storage_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['endDate'] = $data['filterData']['endDate'] ?? $filteredData['endDate'];
            $filteredData['startDate'] = $data['filterData']['startDate'] ?? $filteredData['startDate'];
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }

    public function documentGoodsWithCount(): HasMany
    {
        return $this->hasMany(GoodDocument::class, 'document_id')
            ->selectRaw('document_id, COUNT(*) as total_count')
            ->groupBy('document_id');
    }
}
