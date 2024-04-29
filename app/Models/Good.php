<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'vendor_code', 'description', 'category_id', 'unit_id', 'barcode', 'storage_id', 'good_group_id'];

    public static function bootSoftDeletes()
    {

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(Barcode::class);
    }

    public function goodGroup(): BelongsTo
    {
        return $this->belongsTo(GoodGroup::class, 'good_group_id', 'id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(GoodImages::class, 'good_id');
    }



    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'vendor_code' => $data['filterData']['vendor_code'] ?? null,
            'description' => $data['filterData']['description'] ?? null,
            'name'  => $data['filterData']['name'] ?? null,
            'category_id' => $data['filterData']['category_id'] ?? null,
            'unit_id' => $data['filterData']['unit_id'] ?? null,
            'barcode' => $data['filterData']['barcode'] ?? null,
            'storage_id' => $data['filterData']['storage_id'] ?? null,
        ];
    }
}
