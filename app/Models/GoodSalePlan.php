<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodSalePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'year',
    ];


    public function goodSalePlan() :HasMany
    {
        return $this->hasMany(GoodPlan::class, 'good_sale_plan_id', 'id');
    }

    public function organization() :BelongsTo {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
        ];
    }
}
