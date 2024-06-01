<?php

namespace App\Models;

use App\Repositories\Contracts\SoftDeleteInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable;


class Currency extends Model implements SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'digital_code', 'symbol_code', 'default'];

    public function exchangeRates() :HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'currency_id');
    }
    public static function bootSoftDeletes()
    {

    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'symbol_code' => $this->symbol_code,
            'digital_code' => $this->digital_code,
        ];
    }

    public static function filter(array $data): array
    {
        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'deleted' => $data['deleted'] ?? null,
            'digital_code' => $data['deleted'] ?? null,
            'name' => $data['deleted'] ?? null,
            'symbol_code' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['name'] = $data['filterData']['name'] ?? null;
            $filteredData['digital_code'] = $data['filterData']['digital_code'] ?? null;
            $filteredData['symbol_code'] = $data['filterData']['symbol_code'] ?? null;
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('default', true);
    }

}
