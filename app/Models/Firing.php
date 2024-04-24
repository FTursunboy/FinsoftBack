<?php

namespace App\Models;

use App\Filters\EmployeeMovementFilter;
use App\Filters\FiringFilter;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\SoftDeleteInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firing extends Model implements SoftDeleteInterface
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'employee_id',
        'firing_date',
        'basis',
        'author_id',
        'comment',
    ];


    protected $casts = [
        'date' => 'date',
        'firing_date' => 'date'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(FiringFilter::class);
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'date' => $data['filterData']['date'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'employee_id' =>$data['filterData']['employee_id'] ?? null,
            'hiring_date' => $data['filterData']['hiring_date'] ?? null
        ];
    }
}
