<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportCard extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected function reportEmployees() :HasMany
    {
        return $this->hasMany(ReportEmployees::class, 'report_card_id', 'id');
    }


    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['organization_id'] ?? null,
            'month_id' =>$data['month_id'] ?? null,
        ];
    }

}
