<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryDocument extends DocumentModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'month_id',
        'author_id',
        'comment',
        'active',
    ];


    public function employees(): HasMany
    {
        return $this->hasMany(SalaryDocumentEmployees::class, 'salary_document_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function organization() :BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public static function filterData(array $data): array
    {

        $filteredData = [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'month_id' => $data['month_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'date' => $data['date'] ?? null,
            'author_id' => $data['author_id'] ?? null
        ];

        if (isset($data['filterData'])) {
            $filteredData['month_id'] = $data['filterData']['month_id'] ?? $filteredData['month_id'];
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['date'] = $data['filterData']['date'] ?? $filteredData['date'];
        }


        return $filteredData;
    }

}
