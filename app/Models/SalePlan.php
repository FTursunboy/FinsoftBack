<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'year',
        'type'
    ];

    public function goodSalePlan() :HasMany
    {
        return $this->hasMany(GoodPlan::class, 'sale_plan_id', 'id');
    }

    public function employeeSalePlan() :HasMany
    {
        return $this->hasMany(EmployeePlan::class, 'sale_plan_id', 'id');
    }

    public function storageSalePlan() :HasMany
    {
        return $this->hasMany(StoragePlan::class, 'sale_plan_id', 'id');
    }

    public function operationTypeSalePlan() :HasMany
    {
        return $this->hasMany(OperationTypePlan::class, 'sale_plan_id', 'id');
    }

    public function oldNewClientSalePlan() :HasMany
    {
        return $this->hasMany(OldNewClientPlan::class, 'sale_plan_id', 'id');
    }

    public function organization() :BelongsTo {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'direction' => $data['sort'] ?? 'asc',
            'organization_id' => $data['filterData']['organization_id'] ?? null,
        ];
    }
}
