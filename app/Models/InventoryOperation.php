<?php

namespace App\Models;

use App\Filters\MovementDocumentFilter;
use App\Repositories\Contracts\SoftDeleteInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryOperation extends DocumentModel implements SoftDeleteInterface
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'doc_number',
        'status',
        'active',
        'organization_id',
        'storage_id',
        'author_id',
        'date',
        'comment',
        'currency_id',
        'sum'
    ];


    public static function bootSoftDeletes()
    {

    }

    public function modelFilter()
    {
        return $this->provideFilter(\App\Filters\InventoryOperation::class);
    }

    public function organization() :BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function storage() :BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function currency() :BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function documentGoods()
    {
        return $this->hasMany(GoodDocument::class, 'document_id', 'id');
    }

    public static function filterData(array $data): array
    {

        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'storage_id' =>  $data['storage_id'] ?? null,
            'date' => $data['date'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'currency_id' => $data['currency_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['currency_id'] = $data['filterData']['currency_id'] ?? $filteredData['currency_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['date'] = $data['filterData']['date'] ?? $filteredData['date'];
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }





}
