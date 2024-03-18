<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class OrganizationBill extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{

    use SoftDeletes, Searchable, HasFactory;


    protected $fillable = ['name', 'currency_id', 'bill_number', 'organization_id', 'date', 'comment'];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public static function bootSoftDeletes()
    {

    }

    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'currency_id' => $data['filterData']['currency_id'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'name'  => $data['filterData']['name'] ?? null,
            'bill_number' => $data['filterData']['bill_number'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
            'comment' => $data['filterData']['comment'] ?? null
        ];
    }
}
